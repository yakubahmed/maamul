<?php
// Simple rule-based assistant with EN + SO support
session_start();
header('Content-Type: application/json');

// Ensure AJAX + session
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  echo json_encode(['error' => 'Invalid request']);
  exit;
}

include('../inc/config.php');
include('../path.php');

if (!isset($_SESSION['isLogedIn'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Session expired']);
  exit;
}

// Helpers
function jreply($reply, $suggestions = []){
  echo json_encode(['reply' => $reply, 'suggestions' => $suggestions]);
  exit;
}

function lang() {
  if (!empty($_POST['lang'])) return $_POST['lang'];
  return $_SESSION['lang'] ?? 'so';
}

function norm($s){ return strtolower(trim($s)); }

// Data queries
function db_today_sales_total(mysqli $con){
  $sql = "SELECT COALESCE(SUM(pr_af_dis),0) AS total FROM orders WHERE DATE(order_date)=CURDATE()";
  $re = mysqli_query($con, $sql);
  $row = $re ? mysqli_fetch_assoc($re) : ['total'=>0];
  return (float)($row['total'] ?? 0);
}

function db_unpaid_invoices(mysqli $con){
  $sql = "SELECT COUNT(*) AS c FROM orders WHERE payment_status IS NULL OR payment_status='' OR payment_status NOT IN ('Paid','paid','PAID')";
  $re = mysqli_query($con, $sql);
  $row = $re ? mysqli_fetch_assoc($re) : ['c'=>0];
  return (int)($row['c'] ?? 0);
}

function db_low_stock(mysqli $con, $threshold = 5){
  $threshold = max(0, (int)$threshold);
  $sql = "SELECT COUNT(*) AS c FROM item WHERE qty <= $threshold";
  $re = mysqli_query($con, $sql);
  $row = $re ? mysqli_fetch_assoc($re) : ['c'=>0];
  return (int)($row['c'] ?? 0);
}

function db_top_products(mysqli $con){
  $sql = "SELECT i.item_name, SUM(oi.qty) q FROM order_item oi JOIN item i ON oi.item_id=i.item_id GROUP BY oi.item_id ORDER BY q DESC LIMIT 5";
  $re = mysqli_query($con, $sql);
  $out = [];
  if($re){ while($r = mysqli_fetch_assoc($re)){ $out[] = $r['item_name'].' (' . (int)$r['q'] . ')'; } }
  return $out;
}

// Read input
$msg = isset($_POST['message']) ? (string)$_POST['message'] : '';
$L = lang();
$m = norm($msg);

// Greetings / first load
if ($m === '__hello__' || $m === 'hi' || $m === 'hello' || $m === 'salaam' || $m === 'asalamu calaykum' || $m === 'asalaamu calaykum'){
  if ($L === 'so') {
    jreply(
      'Salaan! Waxaan kuu fududeyn karaa: iibka maanta, kayd hooseeya, qaababka bogagga (tusaale: "Ku dar shay", "Liiska iibka"). Maxaad u baahan tahay?',
      ['iib maanta', 'kayd hooseeya', 'ku dar shay', 'liiska iibka']
    );
  } else {
    jreply(
      'Hello! I can help with today’s sales, low stock, quick links (e.g. "Add item", "Sales list"). What do you need?',
      ['today sales', 'low stock', 'add item', 'sales list']
    );
  }
}

// Intent detection (very simple keyword matching)
$isSalesToday = (strpos($m,'today sales')!==false) || (strpos($m,'sales today')!==false) || (strpos($m,'iib maanta')!==false) || (strpos($m,'maanta iib')!==false);
$isLowStock = (strpos($m,'low stock')!==false) || (strpos($m,'kayd hooseeya')!==false) || (strpos($m,'alaab dhamaad')!==false) || (strpos($m,'kayd yar')!==false);
$isUnpaid   = (strpos($m,'unpaid')!==false) || (strpos($m,'aan la bixin')!==false) || (strpos($m,'dayn')!==false);
$isTopProd  = (strpos($m,'top product')!==false) || (strpos($m,'best seller')!==false) || (strpos($m,'alaabta ugu iibka badan')!==false);

// Navigation shortcuts
$navMap = [
  // EN
  'add item' => BASE_URL.'items/add',
  'items list' => BASE_URL.'items/list',
  'add supplier' => BASE_URL.'supplier/add',
  'suppliers' => BASE_URL.'supplier/list',
  'new sale' => BASE_URL.'sales/new',
  'sales list' => BASE_URL.'sales/history',
  'new purchase' => BASE_URL.'purchase/new',
  'purchases' => BASE_URL.'purchase/history',
  'add expense' => BASE_URL.'expense/new',
  'expenses' => BASE_URL.'expense/list',
  // SO
  'ku dar shay' => BASE_URL.'items/add',
  'liiska alaabta' => BASE_URL.'items/list',
  'ku dar shirkad' => BASE_URL.'supplier/add',
  'liiska shirkadaha' => BASE_URL.'supplier/list',
  'iib cusub' => BASE_URL.'sales/new',
  'liiska iibka' => BASE_URL.'sales/history',
  'iibsasho cusub' => BASE_URL.'purchase/new',
  'liiska iibsashada' => BASE_URL.'purchase/history',
  'kharash cusub' => BASE_URL.'expense/new',
  'liiska kharashaadka' => BASE_URL.'expense/list',
];

foreach ($navMap as $k=>$url){
  if (strpos($m, $k) !== false) {
    if ($L==='so') {
      jreply('Furitaanka: '.$k.' → '.$url, ['iib maanta', 'kayd hooseeya']);
    } else {
      jreply('Opening: '.$k.' → '.$url, ['today sales', 'low stock']);
    }
  }
}

// Optional thresholds like: "low stock < 3"
$threshold = 5;
if (preg_match('/(low stock|kayd hooseeya)[^0-9]*([0-9]{1,3})/i', $m, $mm)){
  $threshold = (int)$mm[2];
}

// Handle intents
if ($isSalesToday){
  $total = db_today_sales_total($con);
  if ($L==='so') jreply('Wadarta iibka maanta: $'.number_format($total,2), ['kayd hooseeya', 'liiska iibka']);
  else jreply('Today’s sales total: $'.number_format($total,2), ['low stock', 'sales list']);
}

if ($isLowStock){
  $count = db_low_stock($con, $threshold);
  if ($L==='so') jreply('Alaab kaydkeedu ka hooseeyo '.$threshold.': '.$count, ['iib maanta', 'liiska alaabta']);
  else jreply('Items at or below stock '+$threshold+': '+$count, ['today sales', 'items list']);
}

if ($isUnpaid){
  $c = db_unpaid_invoices($con);
  if ($L==='so') jreply('Foomamka aan weli la bixin: '.$c, ['iib maanta', 'liiska iibka']);
  else jreply('Unpaid invoices: '.$c, ['today sales', 'sales list']);
}

if ($isTopProd){
  $tops = db_top_products($con);
  if ($L==='so') jreply('Alaabta ugu iibka badan: '.(count($tops)?implode(', ',$tops):'Ma jiraan xog.'), ['iib maanta', 'liiska alaabta']);
  else jreply('Top selling products: '.(count($tops)?implode(', ',$tops):'No data.'), ['today sales', 'items list']);
}

// Fallback help
if ($L==='so'){
  jreply(
    'Waan fahmi waayay. Tusaalooyin: "iib maanta", "kayd hooseeya", ama isticmaal toobiyeyaasha sida "Ku dar shay".',
    ['iib maanta', 'kayd hooseeya', 'ku dar shay', 'liiska iibka']
  );
} else {
  jreply(
    'Sorry, I didn\'t get that. Try: "today sales", "low stock", or quick links like "Add item".',
    ['today sales', 'low stock', 'add item', 'sales list']
  );
}

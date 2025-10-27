<?php
include('../path.php');
include('../inc/session_config.php');
session_start();
include('../inc/config.php');

header('Content-Type: application/json; charset=UTF-8');

if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
  echo json_encode(['error' => 'not_logged_in']);
  exit;
}

function shortNumber($num){
  $units = ['', 'K', 'M', 'B', 'T'];
  $i = 0;
  while($num >= 1000 && $i < count($units)-1){
    $num /= 1000;
    $i++;
  }
  return round($num, 1) . $units[$i];
}

$msg = isset($_POST['message']) ? trim(strtolower($_POST['message'])) : '';
if($msg === ''){
  echo json_encode(['reply' => 'Please type your question. For example: "current balance", "today sales", "low stock"']);
  exit;
}

$reply = '';
$links = [];

// Simple intents
if(strpos($msg, 'current balance') !== false || strpos($msg, 'balance') !== false){
  $sales = 0; $purchases = 0; $expense = 0;
  if($r = mysqli_query($con, "SELECT SUM(amount) FROM payment")){ $rw = mysqli_fetch_array($r); $sales = (float)($rw[0] ?: 0); }
  if($r = mysqli_query($con, "SELECT SUM(amount) FROM pur_payments")){ $rw = mysqli_fetch_array($r); $purchases = (float)($rw[0] ?: 0); }
  if($r = mysqli_query($con, "SELECT SUM(amount) FROM expense")){ $rw = mysqli_fetch_array($r); $expense = (float)($rw[0] ?: 0); }
  $bal = $sales - $purchases - $expense;
  $reply = 'Current Balance: $' . number_format($bal, 2);
  $links[] = ['label' => 'Account List', 'url' => BASE_URL . 'account/list'];
}
elseif(strpos($msg, 'today') !== false && strpos($msg, 'sale') !== false){
  $today = date('Y-m-d');
  $v = 0; if($r = mysqli_query($con, "SELECT SUM(pr_af_dis) FROM orders WHERE DATE(order_date) = '$today'")){ $rw = mysqli_fetch_array($r); $v = (float)($rw[0] ?: 0); }
  $reply = "Today's Sales: $" . number_format($v, 2) . " (" . shortNumber($v) . ")";
  $links[] = ['label' => "Sales History", 'url' => BASE_URL . 'sales/history'];
}
elseif((strpos($msg, 'purchase') !== false || strpos($msg, 'purchases') !== false) && strpos($msg, 'today') !== false){
  $today = date('Y-m-d');
  $v = 0; if($r = mysqli_query($con, "SELECT SUM(gtotal) FROM purchase WHERE DATE(pur_date) = '$today'")){ $rw = mysqli_fetch_array($r); $v = (float)($rw[0] ?: 0); }
  $reply = "Today's Purchases: $" . number_format($v, 2) . " (" . shortNumber($v) . ")";
  $links[] = ['label' => 'Purchase History', 'url' => BASE_URL . 'purchase/history'];
}
elseif(strpos($msg, 'low stock') !== false || (strpos($msg, 'stock') !== false && strpos($msg, 'low') !== false)){
  $count = 0; if($r = mysqli_query($con, "SELECT COUNT(*) FROM item WHERE qty <= 10")){ $rw = mysqli_fetch_array($r); $count = (int)($rw[0] ?: 0); }
  $reply = 'Low stock items: ' . $count;
  $links[] = ['label' => 'Items List', 'url' => BASE_URL . 'items/list'];
}
elseif(strpos($msg, 'sales due') !== false || (strpos($msg, 'sales') !== false && strpos($msg, 'due') !== false)){
  $v = 0; if($r = mysqli_query($con, "SELECT SUM(balance) FROM orders")){ $rw = mysqli_fetch_array($r); $v = (float)($rw[0] ?: 0); }
  $reply = 'Total Sales Due: $' . number_format($v, 2) . ' (' . shortNumber($v) . ')';
  $links[] = ['label' => 'Sales History', 'url' => BASE_URL . 'sales/history'];
}
elseif(strpos($msg, 'purchase due') !== false || (strpos($msg, 'purchase') !== false && strpos($msg, 'due') !== false)){
  $v = 0; if($r = mysqli_query($con, "SELECT SUM(balance) FROM purchase")){ $rw = mysqli_fetch_array($r); $v = (float)($rw[0] ?: 0); }
  $reply = 'Total Purchase Due: $' . number_format($v, 2) . ' (' . shortNumber($v) . ')';
  $links[] = ['label' => 'Purchase History', 'url' => BASE_URL . 'purchase/history'];
}
elseif(strpos($msg, 'report') !== false || strpos($msg, 'reports') !== false){
  $reply = 'Open reports:';
  $links[] = ['label' => 'Item Details', 'url' => BASE_URL . 'reports/item-details'];
  $links[] = ['label' => 'Account Balance', 'url' => BASE_URL . 'reports/account-balance-report'];
}
elseif(strpos($msg, 'help') !== false){
  $reply = 'Try: "current balance", "today sales", "low stock", "sales due", "purchase due", or type "reports".';
}
else{
  $reply = 'Sorry, I did not understand. Try: "current balance", "today sales", "low stock", "reports"';
}

echo json_encode(['reply' => $reply, 'links' => $links]);
exit;


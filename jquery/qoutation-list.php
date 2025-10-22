<?php 
include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['viewSingleQuote'])){
    $id = $_POST['viewSingleQuote'];

    $stmt = "SELECT quotation.qoutation_id, quotation.grand_total,quotation.ser, quotation.date ,users.fullname, customer.cust_name, customer.cust_phone, quotation.total,quotation.discount FROM quotation, users,customer WHERE quotation.cust_id = customer.customer_id AND quotation.created_by = users.userid AND quotation.qoutation_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $id = $row['qoutation_id'];
        $ser = $row['ser'];
        $odate = date('d-m-Y @ h:i:a', strtotime($row['date'])) ;
        $cust = $row['cust_name'];
        $cphone = $row['cust_phone'];

        $username = $row['fullname'];
        $tamount = $row['total'];
        $discount = $row['discount'];
        $gtotal = $row['grand_total'];


        echo "

        <div class='col-md-4'>
        <p><strong>Quotation date:</strong></p>
        <p>$odate</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Invoice number:</strong></p>
        <p>$ser</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Customer:</strong></p>
        <p>$cust</p>
      </div>
      <hr>
 
      <div class='col-md-4'>
        <p><strong>Order Taken by:</strong></p>
        <p>$username </p>
      </div>
      <div class='col-md-4'>
        <p><strong>Total Amount:</strong></p>
        <p>$tamount $</p>
      </div>
   
      <div class='col-md-4'>
      <p><strong>Dicount:</strong></p>
      <p>$discount $</p>
      </div>

      <div class='col-md-4'>
      <p><strong>Grand total:</strong></p>
      <p>$gtotal $</p>
      </div>
 
        
        ";
    }

    echo "
    <div class='col-md-12'>
        <ul class='nav nav-tabs' id='myTab' role='tablist'>
            <li class='nav-item' role='presentation'>
            <a class='nav-link active' id='home-tab' data-toggle='tab' href='#home' role='tab' aria-controls='home' aria-selected='true'>Order items</a>
            </li>
           
        </ul> 
        <div class='tab-content' id='myTabContent'>
        ";

        $stmt = "SELECT quotation.qoutation_id, quotation_item.qitem_id , quotation_item.qty, quotation_item.discount, quotation_item.price, quotation_item.sub_total, item.item_name, item.item_image FROM quotation, quotation_item, item WHERE quotation.qoutation_id = quotation_item.quotation_id AND quotation_item.item_id = item.item_id AND quotation.qoutation_id = $id";
        $result = mysqli_query($con, $stmt);
        echo "
        <div class='tab-pane fade show active' id='home' role='tabpanel' aria-labelledby='home-tab'>
            <table class='table table-table-striped'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Item name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Discount</th>
                    <th>Sub total</th>
                </tr>
                </thead>
                <tbody>
                
               
        ";
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $qty = $row['qty'];
            $dis = $row['discount'];
            $price = $row['price'];
            $stotal = $row['sub_total'];
            $name = $row['item_name'];
            $img = $row['item_image'];
            $i++;
            echo "
                <tr>
                    <td>$i</td>
                    <td>$name</td>
                    <td>$price</td>
                    <td>$qty</td>
                    <td>$dis</td>
                    <td>$stotal</td>
                </tr>
            ";
        }

        echo "


                        
        </tbody>
    </table>
</div>
</div>
</div>
";


}

if(isset($_POST['addPayAmount'])){
  $id = $_POST['addPayAmount'];
  $stmt = "SELECT balance FROM orders WHERE order_id = $id";
  $result = mysqli_query($con, $stmt);
  while($row = mysqli_fetch_array($result)){
    echo $row[0];
  }
}

if(isset($_POST['btnDelSale'])){
  $id = intval($_POST['btnDelSale']);
  
  if(empty($id)){
    echo "invalid_input";
    exit;
  }

  $stmt = "DELETE FROM quotation WHERE qoutation_id = $id";
  $result = mysqli_query($con, $stmt);
  
  if(!$result){
    echo "error: " . mysqli_error($con);
    exit;
  }
  
  if($result){
    echo "deleted";
  }else{
    echo "failed";
  }
}



if(isset($_POST['order'])){
  $id = $_POST['order'];
  $amount = $_POST['amount'];
  $des = $_POST['desc'];
  $date = $_POST['sdate'];
  $pmeth = $_POST['pmeth'];
  $uid = $_SESSION['uid'];

  $sql = "SELECT * FROM orders WHERE order_id = $id";
  $res = mysqli_query($con, $sql);
  $ro = mysqli_fetch_assoc($res); 
  $balance = $ro['balance'];
  $gtotal = $ro['pr_af_dis'];


  $pstatus = "";

  if($amount < $balance ){ $pay_status = 'Partial payment'; }
  if($amount == 0 ){ $balance = 'Not paid'; }
  if($amount == $balance ){ $pay_status = 'Paid'; }

  $balance = $gtotal - $amount;

  if($amount < $balance ){
    $stmt = "UPDATE orders SET payment_status = '$pay_status', balance = balance - $amount, 
    amount = amount + $amount WHERE  order_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
      $sql = "INSERT INTO payment (order_id, amount, account, date, created_by, description) 
      VALUES ($id,$amount,$pmeth,'$date', '$uid', '$des')";
      $re = mysqli_query($con, $sql);
      echo "updated";
    }
  }else if($amount == $balance){
    $stmt = "UPDATE orders SET payment_status = '$pay_status', balance = balance - $amount, 
    amount = $amount WHERE order_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
      $sql = "INSERT INTO payment (order_id, amount, account, date, created_by, description) 
      VALUES ($id,$amount,$pmeth,'$date', '$uid', '$des')";
      $re = mysqli_query($con, $sql);
      echo "updated";
    }else{
      echo mysqli_error($con);

    }
  }


 


}

if(isset($_POST['download'])){
  
require('../fpdf/fpdf.php');
$id = $_POST['download'];

$stmt = "
SELECT orders.order_id, orders.payment_deadline ,orders.ser, orders.trans_date ,users.fullname,orders.order_date, customer.cust_name, 

    customer.cust_phone,customer.cust_email, customer.cust_addr, orders.order_status, orders.amount,orders.pr_af_dis,orders.discount_on_all, orders.pr_be_dis ,orders.balance, orders.payment_status 
    FROM orders, users,customer WHERE orders.cust_id = customer.customer_id AND orders.order_by = users.userid AND orders.order_id = $id
";
$result = mysqli_query($con, $stmt);
$row = mysqli_fetch_assoc($result);
$id = $row['order_id'];
$ser = $row['ser'];
$odate = date('d-m-Y', strtotime($row['order_date'])) ;
$tdate = date('d-m-Y', strtotime($row['trans_date'])) ;
$cust = $row['cust_name'];
$cphone = $row['cust_phone'];
$email = $row['cust_email'];
$address = $row['cust_addr'];
$ostatus = $row['order_status'];
$pamount = $row['amount'];
$balance = $row['balance'];
$pstatus = $row['payment_status'];
$username = $row['fullname'];
$tamount = $row['pr_af_dis'];
$discount = $row['discount_on_all'];
$total = $row['pr_be_dis'];
$pdate = $row['payment_deadline'];


class PDF extends FPDF
{
  var $B=0;
  var $I=0;
  var $U=0;
  var $HREF='';
  var $ALIGN='';

  function WriteHTML($html)
  {
      //HTML parser
      $html=str_replace("\n",' ',$html);
      $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
      foreach($a as $i=>$e)
      {
          if($i%2==0)
          {
              //Text
              if($this->HREF)
                  $this->PutLink($this->HREF,$e);
              elseif($this->ALIGN=='center')
                  $this->Cell(0,5,$e,0,1,'C');
              else
                  $this->Write(5,$e);
          }
          else
          {
              //Tag
              if($e[0]=='/')
                  $this->CloseTag(strtoupper(substr($e,1)));
              else
              {
                  //Extract properties
                  $a2=explode(' ',$e);
                  $tag=strtoupper(array_shift($a2));
                  $prop=array();
                  foreach($a2 as $v)
                  {
                      if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                          $prop[strtoupper($a3[1])]=$a3[2];
                  }
                  $this->OpenTag($tag,$prop);
              }
          }
      }
  }

  function OpenTag($tag,$prop)
  {
      //Opening tag
      if($tag=='B' || $tag=='I' || $tag=='U')
          $this->SetStyle($tag,true);
      if($tag=='A')
          $this->HREF=$prop['HREF'];
      if($tag=='BR')
          $this->Ln(5);
      if($tag=='P')
          $this->ALIGN=$prop['ALIGN'];
      if($tag=='HR')
      {
          if( !empty($prop['WIDTH']) )
              $Width = $prop['WIDTH'];
          else
              $Width = $this->w - $this->lMargin-$this->rMargin;
          $this->Ln(2);
          $x = $this->GetX();
          $y = $this->GetY();
          $this->SetLineWidth(0.4);
          $this->Line($x,$y,$x+$Width,$y);
          $this->SetLineWidth(0.2);
          $this->Ln(2);
      }
  }

  function CloseTag($tag)
  {
      //Closing tag
      if($tag=='B' || $tag=='I' || $tag=='U')
          $this->SetStyle($tag,false);
      if($tag=='A')
          $this->HREF='';
      if($tag=='P')
          $this->ALIGN='';
  }

  function SetStyle($tag,$enable)
  {
      //Modify style and select corresponding font
      $this->$tag+=($enable ? 1 : -1);
      $style='';
      foreach(array('B','I','U') as $s)
          if($this->$s>0)
              $style.=$s;
      $this->SetFont('',$style);
  }

  function PutLink($URL,$txt)
  {
      //Put a hyperlink
      $this->SetTextColor(0,0,255);
      $this->SetStyle('U',true);
      $this->Write(5,$txt,$URL);
      $this->SetStyle('U',false);
      $this->SetTextColor(0);
  }
// Page header
function Header()
{
    // Logo
    $this->Image('../fpdf/invoice_header.png',0,0,210);
    // Arial bold 15
    // $this->SetFont('Arial','B',15);
    // $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'R');
    // Move to the right
    //$this->Cell(80);
    // Title
   // $this->Cell(30,10,'Title',1,0,'C');
    // Line break
    $this->Ln($this->SetY(35));
}

// Page footer
function Footer()
{
  global $username;
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Stamp
    $this->Image('../fpdf/stamp.png',10, $this->GetY() - 35, 50);
    $this->Image('../fpdf/payment.png',80, $this->GetY() - 35, 110);
    $this->Image('../fpdf/footer.png',0, $this->GetY() - 0, 190);
    $this->Image('../fpdf/no.png',183, $this->GetY() - 0, 30);
    $this->Cell(0,-40, "Processed by: " . $username);
    // Arial italic 8

    $this->SetTextColor(255,255,255);
    $this->SetFont('Arial','B',15);
    $this->Cell(10,12,$this->PageNo().'/{nb}',0,0,'R');

    //$this->setFillColor(230,230,230);
    //$this->Cell(190,10,'  Website: www.submalco.so     Email: info@submalco.so     Phone: +252 615 326 221    Location: Jowhar, Somalia   ',0,0,'LR', 1);
    // Page number
     //$this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'L');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 65);
$pdf->SetMargins(10, 60,10);
$pdf->SetTitle('Sales Invoice');
$pdf->SetFont('Times','B',14);

//Title
$pdf->Cell(110,40,"INVOICE TO: ",0,0,'L');
$pdf->Cell(75,40,"INVOICE: ",0,0,'C');
//New line
$pdf->Ln(15);
$pdf->SetFont('Times','',11);




// Name Colummn
$pdf->Cell(110, 20, $cust);

//Invoice No
$pdf->SetFont('Times','B',11);
$pdf->Cell(40, 20, "Invoice No: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20,  $ser, 0,0, "L");

//New Line
$pdf->Ln(5);
//Phone
$pdf->Cell(110, 20, $cphone);

//Date
$pdf->SetFont('Times','B',11);
$pdf->Cell(40, 20, "Date: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20, $odate, 0,0, "L");

//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, $email);

//Payment statys
$pdf->SetFont('Times','B',11);
$pdf->Cell(40, 20, "Order status: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20, $ostatus, 0,0, "L");

//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20,$address);

//Payment statys
$pdf->SetFont('Times','B',11);
$pdf->Cell(40, 20, "Payment status: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20,$pstatus, 0,0, "L");

//Line
// $pdf->Ln(80);
// $pdf->SetDrawColor(220,220,220);
// $pdf->Line(10, 80, 210-10, 80);

//Table

$pdf->Ln(20);
$pdf->SetFont('Times','B',12);
$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);

//Table Header
$pdf->Cell(10, 10, "#", 0, 0, "LR", 1);
$pdf->Cell(60, 10, "Item description", 0, 0, "L",1);
$pdf->Cell(30, 10, "Price", 0,0,"L",1);
$pdf->Cell(30, 10, "Qty", 0,0,"L",1);
$pdf->Cell(30, 10, "Discount", 0,0,"L",1);
$pdf->Cell(30, 10, "Sub Total", 0,1,"L",1);

//Table Body
$pdf->setFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(122,192,67);
$pdf->SetFont('Times','',12);

//Table body
$i=0;
for($i=0; $i<=100; $i++){
$sql = "SELECT orders.order_id, order_item.order_item_id, order_item.qty, order_item.discount, order_item.price, order_item.sub_total, item.item_name, item.item_image FROM orders, order_item, item 
WHERE orders.order_id = order_item.order_id AND order_item.item_id = item.item_id AND order_item.order_id = $id";
$re = mysqli_query($con, $sql);
  while($rw = mysqli_fetch_assoc($re)){
    $qty = $rw['qty'];
    $dis = $rw['discount'];
    $price = $rw['price'];
    $stotal = $rw['sub_total'];
    $name = $rw['item_name'];
    if($dis == "") {$dis = 0;}
    //$img = $row['item_image'];
    $i++;
    $pdf->Cell(10, 7, $i, 1, 0, "C", 1);
    $pdf->Cell(60, 7, $name, 1, 0, "L",1);
    $pdf->Cell(30, 7, $price . " $", 1,0,"C",1);
    $pdf->Cell(30, 7, $qty, 1,0,"C",1);
    $pdf->Cell(30, 7, $dis, 1,0,"C",1);
    $pdf->Cell(30, 7, $stotal . " $", 1,1,"C",1);
  }
}

// Table footer
$pdf->SetFont('Times','',12);
//$pdf->setFillColor(230,230,230);


$pdf->SetFont('Times','',12);
$pdf->Cell(160, 7, "Grand Total", 1, 0, "R", 1);
$pdf->SetFont('Times','B',12);
$pdf->Cell(30, 7, $tamount . " $", 1, 1, "C", 1);

$pdf->Ln(5);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetFont('Times','',12);

if(!empty($pdate)){
  $pdf->WriteHTML("<div style='padding: 2% 2%; color:red;' > 
  <h3><b>Additional Information:</b> </h3> <br>
  1. The deadline to pay the remaining balance is  <b>15, June 2022</b>. <br>
  2. Once the payment is processed it's not refundable.<br>
  3. If you find any issue on this invoice please contact us. 
  
  </div> ");

 // $pdf->MultiCell(190, 6," NOTE:\r\n 1. The deadline to pay the remaining balance is  15, June 2022.\r\n 2. Once the payment is processed it's not refundable.\r\n 3. If you find any issue on this invoice please contact us. ",1,'L',1);
}else{
  $pdf->MultiCell(190, 6," NOTE:\r\n 1. Once the payment is processed it's not refundable\r\n 2. If you find any issue on this invoice please contact us. ",1,'L',1);

}












//$pdf->Output(F,'directory/filename.pdf');

$pdf->Output('D',  ' Sale_' . $ser . '.pdf');;
}

if(isset($_POST['quotation_id'])){
  $id = $_POST['quotation_id'];

  
  echo "
  <script src='../assets/vendor/jquery/jquery.min.js'></script>
  <script src='../assets/vendor/popper.js/umd/popper.min.js'></script>
  <script src='../assets/vendor/bootstrap/js/bootstrap.min.js'></script> <!-- END BASE JS -->
  <script src='../assets/vendor/flatpickr/flatpickr.min.js'></script>
  <script src='../assets/javascript/pages/profile-demo.js'></script> <!-- END PAGE LEVEL JS -->

  <script src='../assets/javascript/theme.min.js'></script> <!-- END THEME JS -->


  ";
  
  $stmt = "SELECT * FROM quotation WHERE qoutation_id  = $id";
  $result = mysqli_query($con, $stmt);
  while($row = mysqli_fetch_assoc($result)){
    $total = $row['grand_total'];
    echo "
    <div class='form-group col-md-6'>
    <label for=''>Sale status *</label>
    <input type='hidden' name='qoutation_id' id='qoutation_id' value='$id'>
    <select name='status' id='status' class='form-control rounded-0' required>
      <option value=''>Select order status</option>
      <option value='Ordered'>Ordered</option>
      <option value='Confirmed'>Confirmed</option>
      <option value='Delivered'>Delivered</option>    
    </select>
  </div>

  <div class='form-group col-md-6'>
    <label for=''> Sale date *</label>
    <input id='flatpickr03' type='hidden'  placeholder='Select sale date' name='sdate' id='sdate' required='' class='form-control flatpickr-input rounded-0 sdate' data-toggle='flatpickr' required data-alt-input='true' data-alt-format='F j, Y' data-date-format='Y-m-d'>
    <p class='text-danger d-none' id='sdateMsg'></p>
  </div>

  <div class='form-group col-md-6'>
    <label for=''> Due date * </label>
    <input id='flatpickr03' type='hidden' required placeholder='Select due date' name='ddate' id='ddate' class='form-control flatpickr-input rounded-0 ddate' data-toggle='flatpickr' required='' data-alt-input='true' data-alt-format='F j, Y' data-date-format='Y-m-d'>
    <p class='text-danger d-none' id='sdateMsg'></p>
  </div>

  <div class='form-group col-md-6'>
    <label for=''> Purchase order no </label>
    <input type='text' name='pno' id='pno' autocomplete='off'  class='form-control rounded-0' placeholder='Enter purchase order number'>
    <p class='text-danger d-none' id='sdateMsg'></p>
  </div>

  <div class='form-group col-md-12'>
  <div class='alert alert-primary text-center'>
    -- Payment information -- 
  </div>
  </div>

  <div class='form-group col-md-6'>
    <label for=''>Payment method </label>
    <select name='pmeth' id='bss4' class='form-control pmeth rounded-0' data-toggle='selectpicker'  data-live-search='true' >
      <option data-tokens='' value=''>Select payment method  </option>
  ";    
        $stmt = 'SELECT * FROM account';
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
          $idd = $row['account_id'];
          $name = $row['account_name'];
          $num = $row['account_number'];
          echo "
            <option data-tokens='$num' value='$idd'> $name - $num  </option>
          
          ";
        }
      
      echo "
    </select>
  </div>

  <div class='form-group col-md-6'>
    <label for=''>Amount </label>
    <input type='hidden' name='tamount' id='tamount' value='$total'>
    <input type='number' autocomplete='off'  min='0' name='amount' id='amount' value='0.00'  step='0.01' class='form-control rounded-0' placeholder='0.00'>
    
  </div>

  <div class='form-group col-md-12'>

    <label for=''>Balance</label>

    <input type='number' name='balance' id='balance' value='$total' readonly step='0.01' class='form-control rounded-0' placeholder='0.00'>
  </div>

    ";

  }
}

if(isset($_POST['transfer_quot'])){
  // Validate inputs
  $id = intval($_POST['transfer_quot']);
  $tamount = floatval($_POST['total_amount']);
  $balance = floatval($_POST['balance']);
  $status = mysqli_real_escape_string($con, $_POST['status']);
  $ddate = mysqli_real_escape_string($con, $_POST['duedate']);
  $sdate = mysqli_real_escape_string($con, $_POST['saledate']);
  $pno = mysqli_real_escape_string($con, $_POST['pno']);
  $pamount = floatval($_POST['paid_amount']);
  $account = intval($_POST['account']);
  
  if(empty($id)){
    echo "missing_fields";
    exit;
  }
  
  // Check if quotation exists
  $check_quot = "SELECT COUNT(*) FROM quotation WHERE qoutation_id = $id";
  $check_result = mysqli_query($con, $check_quot);
  $check_row = mysqli_fetch_array($check_result);
  
  if($check_row[0] == 0){
    echo "quotation_not_found";
    exit;
  }
  
  // Check if quotation has items
  $check_items = "SELECT COUNT(*) FROM quotation_item WHERE quotation_id = $id";
  $items_result = mysqli_query($con, $check_items);
  $items_row = mysqli_fetch_array($items_result);
  
  if($items_row[0] == 0){
    echo "no_items";
    exit;
  }

  $stmt = "INSERT INTO orders (cust_id, order_date, discount_on_all, pay_method, payment_deadline, warehouse, order_by,trans_date)
  SELECT cust_id, date, discount, account, due_date, warehouse, created_by, created_date FROM quotation WHERE qoutation_id = $id";
  $result = mysqli_query($con, $stmt);
  
  if(!$result){
    echo "error: " . mysqli_error($con);
    exit;
  }
  
  if($result){
    $last_id = mysqli_insert_id($con);
    $orderid = $last_id;

    // Fixed: Added WHERE clause
    $sql = "SELECT * FROM quotation_item WHERE quotation_id = $id";
    $res = mysqli_query($con, $sql);
    
    if(!$res){
      echo "error: " . mysqli_error($con);
      exit;
    }
    
    while($rw = mysqli_fetch_assoc($res)){
      $item = $rw['item_id'];
      $price = $rw['price'];
      $qty = $rw['qty'];
      $dis = isset($rw['discount']) ? $rw['discount'] : 0;
      $stotal = $rw['sub_total'];

      $stm = "INSERT INTO order_item(order_id, item_id, qty, discount, price, sub_total) VALUES (
        '$orderid', '$item', '$qty', '$dis', '$price', '$stotal') ";
      $re = mysqli_query($con, $stm);
      
      if(!$re){
        echo "error: " . mysqli_error($con);
        exit;
      }
    }

    $qu = "SELECT * FROM quotation WHERE qoutation_id = $id";
    $exec = mysqli_query($con, $qu);
    $rs = mysqli_fetch_assoc($exec);
    $total_be_dis = $rs['total'];
    $discount = $rs['discount'];
    $total = $rs['grand_total'];

    if(empty($pamount)){$pamount = 0;} // Fixed: was == instead of =

    if($pamount == 0 ){ $pay_status = 'Not paid'; }
    else if($pamount < $total ){ $pay_status = 'Partial payment'; }
    else if($pamount == $total ){ $pay_status = 'Paid'; }



    $stmt = "SELECT COUNT(*) FROM orders";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);

    $serial = "INV-" . sprintf("%04d", $row[0] + 1);

    $sql = "UPDATE orders SET ser='$serial', order_status = '$status', po_number = '$pno', payment_deadline='$ddate', 
    discount_on_all = '$dis', pr_be_dis='$total_be_dis',pr_af_dis = '$tamount', amount='$pamount', balance= '$balance', payment_status='$pay_status'
    WHERE order_id = $orderid";
    $res = mysqli_query($con, $sql);
    
    if(!$res){
      echo "error: " . mysqli_error($con);
      exit;
    }
    
    if($res){
      // Check if user is logged in
      if(!isset($_SESSION['uid'])){
        echo "session_expired";
        exit;
      }
      
      $date = date('Y-m-d h:i:s a');
      $uid = $_SESSION['uid'];

      if($pamount != 0){
          $stmt = "INSERT INTO  payment (order_id, amount, account, date, created_by) ";
          $stmt .= "VALUES ('$orderid', '$pamount', '$account', '$date', '$uid')";
          $result = mysqli_query($con,$stmt);
          
          if(!$result){
            echo "error: " . mysqli_error($con);
            exit;
          }
      }

      //Deleting from quotation
      $stmt = "DELETE FROM quotation WHERE qoutation_id = $id";
      $res = mysqli_query($con, $stmt);
      
      if(!$res){
        echo "error: " . mysqli_error($con);
        exit;
      }
      
      if($res){
        echo 'success';
      }
    }


   
  }


  


}

?>
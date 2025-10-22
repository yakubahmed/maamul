<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');
require('../fpdf/fpdf.php');

//getting previous balance 
function balance($id){
    global $con; 
    $stmt = "SELECT SUM(balance) FROM orders WHERE order_id <= $id    ";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_array($result); 

    return $row[0];
}

if(isset($_GET['ref'])){
    $id = $_GET['ref'];

    
$stmt = "
SELECT orders.order_id, orders.payment_deadline,orders.po_number ,orders.cust_id ,orders.ser, orders.trans_date ,users.fullname,orders.order_date, customer.cust_name, 

    customer.cust_phone,customer.cust_email,customer.cust_addr, orders.order_status, orders.amount,orders.pr_af_dis,orders.discount_on_all, orders.pr_be_dis ,orders.balance, orders.payment_status 
    FROM orders, users,customer WHERE orders.cust_id = customer.customer_id AND orders.order_by = users.userid AND orders.order_id = $id
";
$result = mysqli_query($con, $stmt);
$row = mysqli_fetch_assoc($result);
$id = $row['order_id'];
$ser = $row['ser'];
$odate = date('d-m-Y', strtotime($row['order_date'])) ;
$tdate = date('d-m-Y H:i:s a', strtotime($row['trans_date'])) ;
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
$pdate = date('d-m-Y', strtotime($row['payment_deadline'])) ;
$po = $row['po_number'];
$cid = $row['cust_id'];

$pbalance = balance($id);


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
    $this->Ln($this->SetY(50));
}

function MergeMulticell($w, $h, $txt, $border = 0)
{
    // Calculate the height of each line in the multicell
    $lineHeight = $h / count(explode("\n", $txt));
    
    // Save the Y position
    $startY = $this->GetY();
    
    // Output the multicell with the specified border
    $this->MultiCell($w, $lineHeight, $txt, $border);
    
    // Set the Y position to the original starting position
    $this->SetY($startY);
    
    // Return the height of the multicell
    return $h;
}

// Page footer
function Footer()
{
  global $username;
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Stamp
 
    $this->Image('../fpdf/footer-a.png',0, $this->GetY() - 10, 210);
   
    $this->SetFont('Arial','',12);
/*
    $this->Cell(0,-40, "Purchased by: ");
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(0, -40, $username . "     ______________________");
*/
    // Arial italic 8

    $this->SetTextColor(255,255,255);
    $this->SetFont('Arial','B',15);
    $this->Cell(197,4,$this->PageNo().'/{nb}',0,0,'C');

 

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
$pdf->SetTitle('Sales Invoice - Baaburaqiis shoes');
$pdf->SetFont('Arial','B',14);

//Title
$pdf->Cell(110,40,"INVOICE TO: ",0,0,'L');
$pdf->Cell(75,40,"",0,0,'C');
//New line
$pdf->Ln(15);
$pdf->SetFont('Arial','B',11);




// Name Colummn
$pdf->Cell(110, 20, $cust);

//Invoice No
$pdf->SetFont('Arial','',10);
$pdf->Cell(40, 20, "Invoice No: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20,  $ser, 0,0, "L");

//New Line
$pdf->Ln(5);
//Phone
$pdf->Cell(110, 20, 'Tell: '. $cphone);

//Date
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 20, "Date: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20, $odate, 0,0, "L");




//New Line
$pdf->Ln(5);

if(empty($email)) {$email == 'N/A';}

//Email
$pdf->Cell(110, 20, 'Email: ' . $email);


//Date


//Order statys
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 20, "Transaction date: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20, $tdate, 0,0, "L");

//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Address: '.$address);

//Order statys
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 20, "Payment status: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20, $pstatus, 0,0, "L");


//New Line
$pdf->Ln(5);




//Line
// $pdf->Ln(80);
// $pdf->SetDrawColor(220,220,220);
// $pdf->Line(10, 80, 210-10, 80);

//Table

$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
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
$pdf->SetFont('Arial','',10);

//Table body
$i=0;

$sql = "SELECT orders.order_id, order_item.order_item_id,unit.shortname, order_item.qty, order_item.discount, order_item.price, order_item.sub_total, item.item_name, item.item_image FROM orders, order_item, item, unit 
WHERE orders.order_id = order_item.order_id AND unit.unit_id = item.unit AND order_item.item_id = item.item_id AND order_item.order_id = $id";
$re = mysqli_query($con, $sql);
  while($rw = mysqli_fetch_assoc($re)){
    $qty = $rw['qty'];
    $dis = $rw['discount'];
    $price = $rw['price'];
    $stotal = $rw['sub_total'];
    $name = $rw['item_name'];
    $unit = $rw['shortname'];
    if($dis == "") {$dis = 0;}
    //$img = $row['item_image'];
    $i++;
    $pdf->Cell(10, 7, $i, 1, 0, "C", 1);
    $pdf->Cell(60, 7, $name, 1, 0, "L",1);
    $pdf->Cell(30, 7, $price . " $", 1,0,"C",1);
    $pdf->Cell(30, 7, $qty . ' ' . $unit, 1,0,"C",1);
    $pdf->Cell(30, 7, $dis, 1,0,"C",1);
    $pdf->Cell(30, 7, $stotal . " $", 1,1,"C",1);
  }


// Table footer
$pdf->SetFont('Arial','',10);
//$pdf->setFillColor(230,230,230);





$pdf->SetFont('Arial','',10);
$pdf->Cell(160, 7, 'Sub Total', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',10);
$pdf->setFillColor(239,239,239);
$pdf->SetTextColor(122,192,67);
$pdf->Cell(30, 7, $total . " $", 1, 1, "C", 1);

$pdf->setFillColor(255,255,255);
$pdf->Cell(190, 7, "", 0, 1, "C", 1);
$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);



//////
$pdf->Cell(40, 7, "Sales discount", 1, 0, "C", 1);
$pdf->Cell(60, 7, "Grand Total", 1, 0, "L",1);
$pdf->Cell(30, 7, "Paid Amount" , 1,0,"C",1);
$pdf->Cell(30, 7, "Balance" , 1,0,"C",1);
$pdf->Cell(30, 7, "Previous Balance", 1,1,"C",1);

$pdf->setFillColor(239,239,239);
$pdf->SetTextColor(0,0,0);

$pdf->Cell(40, 7, $discount, 1, 0, "C", 1);
$pdf->Cell(60, 7, $tamount . ' $', 1, 0, "L",1);
$pdf->Cell(30, 7, $pamount . " $", 1,0,"C",1);
$pdf->Cell(30, 7, $balance . ' $', 1,0,"C",1);
$pdf->Cell(30, 7, $pbalance . ' $', 1,1,"C",1);





$pdf->Ln(5);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetFont('Arial','',10);



$pdf->SetFont('Arial','B',10);

$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);

$pdf->MultiCell(190, 6,"FIIRO GAAR AH ",1,'L',1);
$pdf->SetTextColor(0,0,0);
if(!empty($pdate)){
    
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(190, 8," 1. Fadlan iska hubi xogta warqada ku qoran iyo alaabta aad qaadatay iney is leeyihiin inta aadan bixin.
 2. Shey la gatay lama soo celin karo mana la badali karo.",1,'L',0);

 // $pdf->MultiCell(190, 6," NOTE:\r\n 1. The deadline to pay the remaining balance is  15, June 2022.\r\n 2. Once the payment is processed it's not refundable.\r\n 3. If you find any issue on this invoice please contact us. ",1,'L',1);
}else{

    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(190, 8," 1. Fadlan iska hubi xogta warqada ku qoran iyo alaabta aad qaadatay iney is leeyihiin inta aadan bixin.\n\r
     2. Shey la gatay lama soo celin karo mana la badali karo. \n3. Fadlan bixi lacgta lagaa raba ugu dambeyn: ", $pdate,1,'L',0);

}

$pdf->SetFont('Arial','B',10);


$pdf->MultiCell(190, 10," ~~ MAHADSANID MACAAMIIL ~~",0,'C',0);
$pdf->SetTextColor(122,192,67);
$pdf->Cell(0, 15,"Sales by: " . $username);








//$pdf->Output(F,'directory/filename.pdf');

$pdf->Output('D',  ' Sale_' . $ser . '.pdf');;
}

?>
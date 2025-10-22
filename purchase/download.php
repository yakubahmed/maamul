<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');
require('../fpdf/fpdf.php');

if(isset($_GET['ref'])){
    $id = $_GET['ref'];

    
    $stmt = "SELECT purchase.purchase_id, purchase.ser, purchase.trans_date ,users.fullname,purchase.pur_date, supplier.sup_name, 
    supplier.phone_num, supplier.email_addr,supplier.supp_id  ,supplier.address, purchase.pur_status, purchase.paid_amount,purchase.gtotal,purchase.discount_on_all, purchase.p_be_dis ,purchase.balance, purchase.payment_status 
    FROM purchase, users,supplier WHERE purchase.supp_id = supplier.supp_id AND purchase.pur_by = users.userid AND purchase.purchase_id = $id";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_assoc($result);

    $id = $row['purchase_id'];
    $ser = $row['ser'];
    $odate = date('d-m-Y @ h:i:a', strtotime($row['pur_date'])) ;
    $tdate = date('d-m-Y', strtotime($row['trans_date'])) ;
    $cust = $row['sup_name'];
    $cust = $row['sup_name'];
    $cphone = $row['phone_num'];
    $email = $row['email_addr'];
    $cid = $row['supp_id'];
    $address  = $row['address'];
    $ostatus = $row['pur_status'];
    $pamount = $row['paid_amount'];
    $balance = $row['balance'];
    $pstatus = $row['payment_status'];
    $username = $row['fullname'];
    $tamount = $row['gtotal'];
    $discount = $row['discount_on_all'];
    $total = $row['p_be_dis'];


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
    $this->Image('../fpdf/purchase.png',0,0,210);
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

// Page footer
function Footer()
{
  global $username;
    // Position at 1.5 cm from bottom
    $this->SetY(-20);

    $this->Image('../fpdf/footer-a.png',0, $this->GetY() - 10, 210);
   

    // Arial italic 8
    $this->SetFont('Arial','',12);

    $this->Cell(0,-40, "Purchased by: ");
    $this->Ln(5);
    $this->SetFont('Arial','B',12);
    $this->Cell(0, -40, $username . "     ______________________");

    $this->SetTextColor(255,255,255);
    $this->SetFont('Arial','B',15);
    $this->Cell(190,5,$this->PageNo().'/{nb}',0,0,'C');
 

    // Arial italic 8


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
$pdf->SetTitle('Purchase Order - BAABURAQIIS');
$pdf->SetFont('Arial','B',14);

//Title
$pdf->Cell(110,40,"PURCHASE FROM: ",0,0,'L');
$pdf->Cell(75,40,"",0,0,'C');
//New line
$pdf->Ln(15);
$pdf->SetFont('Arial','B',11);




// Name Colummn
$pdf->Cell(110, 20, $cust);

//Invoice No
$pdf->SetFont('Arial','',12);
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

//Email
$pdf->Cell(110, 20, 'Email: ' . $email);


//Date
// $pdf->SetFont('Arial','',11);
// $pdf->Cell(40, 20, "Due Date: ", 0,0, "R");
// $pdf->SetFont('Arial','',11);
// $pdf->Cell(30, 20, $pdate, 0,0, "L");

//Payment statys
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 20, "Order status: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20, $ostatus, 0,0, "L");

//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Address: '.$address);

//Payment statys
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 20, "Payment status: ", 0,0, "R");
$pdf->SetFont('Arial','',11);
$pdf->Cell(30, 20,$pstatus, 0,0, "L");


//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Suppier ID: '.$cid);

//Payment statys


//Line
// $pdf->Ln(80);
// $pdf->SetDrawColor(220,220,220);
// $pdf->Line(10, 80, 210-10, 80);

//Table

$pdf->Ln(20);
$pdf->SetFont('Arial','B',12);
$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);

//Table Header
$pdf->Cell(10, 10, "#", 0, 0, "C", 1);
$pdf->Cell(60, 10, "Item description", 0, 0, "L",1);
$pdf->Cell(30, 10, "Price", 0,0,"L",1);
$pdf->Cell(30, 10, "Qty", 0,0,"L",1);
$pdf->Cell(30, 10, "Discount", 0,0,"L",1);
$pdf->Cell(30, 10, "Sub Total", 0,1,"L",1);

//Table Body
$pdf->setFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(122,192,67);
$pdf->SetFont('Arial','',12);

//Table body
$i=0;

$sql = "SELECT purchase.purchase_id, pur_items.pur_iem_id ,unit.shortname ,pur_items.qty, pur_items.discount, pur_items.price, pur_items.sub_total, item.item_name, item.item_image FROM purchase, unit, pur_items, item 
WHERE purchase.purchase_id = pur_items.purchase_id AND unit.unit_id = item.unit AND pur_items.item_id = item.item_id AND purchase.purchase_id = $id";
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
$pdf->SetFont('Arial','',12);
//$pdf->setFillColor(230,230,230);

$pdf->SetFont('Arial','',12);
$pdf->Cell(160, 7, 'Sub Total', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',12);

$pdf->Cell(30, 7, $total . " $", 1, 1, "C", 1);
$pdf->setFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','',12);
$pdf->Cell(160, 7, 'Sales Discount', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30, 7, $discount . " $", 1, 1, "C", 1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(160, 7, 'Grand Total', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30, 7, $tamount . " $", 1, 1, "C", 1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(160, 7, 'Paid Amount', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30, 7, $pamount . " $", 1, 1, "C", 1);

$pdf->SetFont('Arial','',12);
$pdf->Cell(160, 7, 'Balance', 0, 0, "R", 0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30, 7, $balance . " $", 1, 1, "C", 1);




$pdf->Ln(5);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetFont('Arial','',12);



$pdf->SetFont('Arial','B',12);

$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);

$pdf->MultiCell(190, 6,"Additional Information ",1,'L',1);
$pdf->SetTextColor(0,0,0);


$pdf->SetFont('Arial','',12);
$pdf->MultiCell(190, 8," 1. Please check all items carefully and notify us of any discrepancy within 24 hours.
 2. Once the payment is processed it's not refundable.",1,'L',0);



$pdf->SetFont('Arial','B',12);


$pdf->MultiCell(190, 12," THANK YOU FOR YOU BUSINESS. ",0,'C',0);












//$pdf->Output(F,'directory/filename.pdf');

$pdf->Output('D',   $ser . '.pdf');;
}

?>
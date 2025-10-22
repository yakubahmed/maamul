<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');
require('../fpdf/fpdf.php');

if(isset($_GET['ref'])){
    $id = $_GET['ref'];

    
$stmt = " SELECT del_note.del_note_id, del_note.invoice_number,del_note.despatch_date,users.fullname ,del_note.tran_date, delivery_method.meth_name, customer.cust_name, customer.cust_phone,customer.customer_id ,customer.cust_email, customer.cust_addr FROM del_note, delivery_method,users ,customer 
    WHERE del_note.cust_id = customer.customer_id AND del_note.del_method = delivery_method.del_meth_id AND users.userid = del_note.delivery_by AND del_note.del_note_id = '$id'";
$result = mysqli_query($con, $stmt);

$row = mysqli_fetch_assoc($result);

$devid = $row['del_note_id'];
$invoice =  $row['invoice_number'];
$did = 'DEL-' . $row['del_note_id'];
$date = date('d-m-Y', strtotime($row['tran_date']));
$ddate = date('d-m-Y', strtotime($row['despatch_date']));
$customer = $row['cust_name'];
$cphone = $row['cust_phone'];
$cemail = $row['cust_email'];
$addr = $row['cust_addr'];
$userby = $row['fullname'];
$cid = $row['customer_id'];
$dmethod = $row['meth_name'];

function total_items_unit($id){
    global $con;
    $stmt = "SELECT shortname FROM unit WHERE unit_id IN (SELECT unit_id FROM del_note_item WHERE item_id = $id )";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    return $row[0];
}


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
    $this->Image('../fpdf/delivery.png',0,0,210);
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
  global $userby;
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Stamp
    $this->Image('../fpdf/stamp.png',140, $this->GetY() - 35, 50);
    // $this->Image('../fpdf/payment.png',80, $this->GetY() - 35, 110);
    $this->Image('../fpdf/footer.png',0, $this->GetY() - 0, 190);
    $this->Image('../fpdf/no.png',183, $this->GetY() - 0, 30);
    $this->Cell(0,-40, "Delivered by: ");
    $this->Ln(5);
    $this->SetFont('Times','B',12);
    $this->Cell(0, -40, $userby . "     ______________________");
    // Arial italic 8

    $this->SetTextColor(255,255,255);
    $this->SetFont('Arial','B',15);
    $this->Cell(10,3,$this->PageNo().'/{nb}',0,0,'R');

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
$pdf->SetTitle('Delivery Note - Submalco');
$pdf->SetFont('Times','B',14);

//Title
$pdf->Cell(110,40,"DELIVERY TO: ",0,0,'L');
$pdf->Cell(75,40,"DELIVERY NOTE: ",0,0,'C');
//New line
$pdf->Ln(15);
$pdf->SetFont('Times','B',11);




// Name Colummn
$pdf->Cell(110, 20, $customer);

//Invoice No
$pdf->SetFont('Times','',12);
$pdf->Cell(40, 20, "Invoice No: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20,  $invoice, 0,0, "L");

//New Line
$pdf->Ln(5);
//Phone
$pdf->Cell(110, 20, 'Tell: '. $cphone);

//Date
$pdf->SetFont('Times','',11);
$pdf->Cell(40, 20, "Date: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20, $date, 0,0, "L");




//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Email: ' . $cemail);


//Date
$pdf->SetFont('Times','',11);
$pdf->Cell(40, 20, "Despatch Date: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20, $ddate, 0,0, "L");

//Payment statys
// $pdf->SetFont('Times','',11);
// $pdf->Cell(40, 20, "Delivery note #: ", 0,0, "R");
// $pdf->SetFont('Times','',11);
// $pdf->Cell(30, 20, $did, 0,0, "L");

//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Address: '.$addr);

//Payment statys
$pdf->SetFont('Times','',11);
$pdf->Cell(40, 20, "Delivery Method: ", 0,0, "R");
$pdf->SetFont('Times','',11);
$pdf->Cell(30, 20,$dmethod, 0,0, "L");


//New Line
$pdf->Ln(5);

//Email
$pdf->Cell(110, 20, 'Customer ID: '.$cid);

//Payment statys
// $pdf->SetFont('Times','',11);
// $pdf->Cell(40, 20, "Delivery Method: ", 0,0, "R");
// $pdf->SetFont('Times','',11);
// $pdf->Cell(30, 20,$dmethod, 0,0, "L");

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
$pdf->Cell(10, 10, "#", 0, 0, "C", 1);
$pdf->Cell(90, 10, "Item description", 0, 0, "L",1);
$pdf->Cell(30, 10, "Ordered", 0,0,"L",1);
$pdf->Cell(30, 10, "Delivered", 0,0,"L",1);
$pdf->Cell(30, 10, "Outstanding", 0,1,"L",1);


//Table Body
$pdf->setFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(122,192,67);
$pdf->SetFont('Times','',12);

//Table body
$i=0;

$i=0;
$stmt = "SELECT item.item_name, del_note_item.qty,del_note_item.item_id ,del_note_item.del_note_item ,delivered, balance, date FROM item, del_note_item WHERE item.item_id = del_note_item.item_id AND del_note_item.del_note_id = $id";
$result = mysqli_query($con, $stmt);
while($row = mysqli_fetch_assoc($result)){
    $i++;
    $iname = $row['item_name'];
    $qty = $row['qty'];
    $del = $row['delivered'];
    $bal = $row['balance'];
    $id = $row['item_id'];
    $total_items_unit = total_items_unit($id);

    $pdf->Cell(10, 7, $i, 1, 0, "C", 1);
    $pdf->Cell(90, 7, $iname, 1, 0, "L",1);
    $pdf->Cell(30, 7, $qty . ' '. total_items_unit($id),  1,0,"C",1);
    $pdf->Cell(30, 7, $del . ' '. $total_items_unit, 1,0,"C",1);
    $pdf->Cell(30, 7, $bal . ' ' .$total_items_unit, 1,1,"C",1);
  
  }


// Table footer
$pdf->SetFont('Times','',12);
//$pdf->setFillColor(230,230,230);




// $pdf->SetFont('Times','',12);
// $pdf->Cell(160, 7, 'Sales Discount', 1, 0, "R", 1);
// $pdf->SetFont('Times','B',12);
// $pdf->Cell(30, 7, $discount . " $", 1, 1, "C", 1);

// $pdf->SetFont('Times','',12);
// $pdf->Cell(160, 7, 'Grand Total', 1, 0, "R", 1);
// $pdf->SetFont('Times','B',12);
// $pdf->Cell(30, 7, $tamount . " $", 1, 1, "C", 1);

// $pdf->SetFont('Times','',12);
// $pdf->Cell(160, 7, 'Paid Amount', 1, 0, "R", 1);
// $pdf->SetFont('Times','B',12);
// $pdf->Cell(30, 7, $pamount . " $", 1, 1, "C", 1);

// $pdf->SetFont('Times','',12);
// $pdf->Cell(160, 7, 'Balance', 1, 0, "R", 1);
// $pdf->SetFont('Times','B',12);
// $pdf->Cell(30, 7, $balance . " $", 1, 1, "C", 1);




$pdf->Ln(5);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetFont('Times','B',12);

$pdf->setFillColor(122,192,67);
$pdf->SetTextColor(255,255,255);

$pdf->MultiCell(190, 6,"Additional Information ",1,'L',1);
$pdf->SetTextColor(0,0,0);


$pdf->SetFont('Times','',12);
$pdf->MultiCell(190, 8," Please check all items carefully and notify us of any discrepancy within 24 hours.",1,'L',0);
$pdf->SetFont('Times','B',16);

$pdf->MultiCell(190, 12," THANK YOU FOR YOU BUSINESS. ",0,'C',0);
















//$pdf->Output(F,'directory/filename.pdf');

$pdf->Output('D',  ' delivery_note_'.$customer . "_" . $did . '.pdf');;
}

?>
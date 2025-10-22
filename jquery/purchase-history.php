<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['viewSingleSale'])){
    $id = $_POST['viewSingleSale'];

    $stmt = "SELECT purchase.purchase_id, purchase.ser, purchase.trans_date ,users.fullname,purchase.pur_date, supplier.sup_name, 
    supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal,purchase.discount_on_all, purchase.p_be_dis ,purchase.balance, purchase.payment_status 
    FROM purchase, users,supplier WHERE purchase.supp_id = supplier.supp_id AND purchase.pur_by = users.userid AND purchase.purchase_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $id = $row['purchase_id'];
        $ser = $row['ser'];
        $odate = date('d-m-Y @ h:i:a', strtotime($row['pur_date'])) ;
        $tdate = date('d-m-Y', strtotime($row['trans_date'])) ;
        $cust = $row['sup_name'];
        $cphone = $row['phone_num'];
        $ostatus = $row['pur_status'];
        $pamount = $row['paid_amount'];
        $balance = $row['balance'];
        $pstatus = $row['payment_status'];
        $username = $row['fullname'];
        $tamount = $row['gtotal'];
        $discount = $row['discount_on_all'];


        echo "

        <div class='col-md-4'>
        <p><strong>Purchase date:</strong></p>
        <p>$odate</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Invoice number:</strong></p>
        <p>$ser</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Supplier:</strong></p>
        <p>$cust</p>
      </div>
      <hr>
      <div class='col-md-4'>
        <p><strong>Purchase status:</strong></p>
        <p><span class='badge  badge-danger'>$ostatus</span></p>
      </div>
      <div class='col-md-4'>
        <p><strong>Payment status:</strong></p>
        <p><span class='badge  badge-info'>$pstatus</span></p>
      </div>
      <div class='col-md-4'>
        <p><strong>Purchased by:</strong></p>
        <p>$username </p>
      </div>
      <div class='col-md-4'>
        <p><strong>Total Amount:</strong></p>
        <p>$tamount $</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Paid Amount:</strong></p>
        <p>$pamount $</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Due Amount</strong></p>
        <p>$balance $</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Discount:</strong></p>
        <p>$discount $</p>
      </div>
        
        ";
    }

    echo "
    <div class='col-md-12'>
        <ul class='nav nav-tabs' id='myTab' role='tablist'>
            <li class='nav-item' role='presentation'>
            <a class='nav-link active' id='home-tab' data-toggle='tab' href='#home' role='tab' aria-controls='home' aria-selected='true'>Purchase items</a>
            </li>
            <li class='nav-item' role='presentation'>
            <a class='nav-link' id='profile-tab' data-toggle='tab' href='#profile' role='tab' aria-controls='profile' aria-selected='false'>Payments</a>
            </li>
        </ul> 
        <div class='tab-content' id='myTabContent'>
        ";

        $stmt = "SELECT purchase.purchase_id, pur_items.pur_iem_id , pur_items.qty, pur_items.price, pur_items.sub_total, item.item_name, item.item_image FROM purchase, pur_items, item 
        WHERE purchase.purchase_id = pur_items.purchase_id AND pur_items.item_id = item.item_id AND purchase.purchase_id = $id";
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
                    <th>Sub total</th>
                </tr>
                </thead>
                <tbody>
                
               
        ";
        $i=0;
        while($row = mysqli_fetch_assoc($result)){
            $qty = $row['qty'];
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
                    <td>$stotal</td>
                </tr>
            ";
        }

        echo " </tbody>
        </table>
    </div>
    <div class='tab-pane fade' id='profile' role='tabpanel' aria-labelledby='profile-tab'> 
    <table class='table table-table-striped'>
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Payment method</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
    ";

    $j=0;
    $stmt = "SELECT pur_payments.*, account.account_name, account.account_number FROM pur_payments, account WHERE pur_payments.account = account.account_id AND pur_payments.pur_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $j++;
        $accname = $row['account_name'];
        $accnum = $row['account_number'];
        $date = date('d-m-Y @ h:i:s a', strtotime($row['date'])) ;
        $amount = $row['amount'];
        $des = $row['description'];

        echo "
            <tr>
                <td>$j</td>
                <td>$date</td>
                <td>$amount</td>
                <td>$accname ($accnum)</td>
                <td>$des</td>
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

    //$stmt = 'SELECT ';
}

if(isset($_POST['addPayAmount'])){
  $id = $_POST['addPayAmount'];
  $stmt = "SELECT gtotal, paid_amount FROM purchase WHERE purchase_id  = $id";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_assoc($result)){
    echo $row['gtotal'] - $row['paid_amount'];
  }
}

if(isset($_POST['btnDelSale'])){
  $id = $_POST['btnDelSale'];

  $stmt = "DELETE FROM purchase WHERE purchase_id = $id";
  $result = mysqli_query($con, $stmt);
  if($result){
    echo "deleted";
  }
}

if(isset($_POST['order'])){
  $id = $_POST['order'];
  $amount = $_POST['amount'];
  $des = $_POST['desc'];
  $date = $_POST['sdate'];
  $pmeth = $_POST['pmeth'];
  $uid = $_SESSION['uid'];

  $sql = "SELECT * FROM purchase WHERE purchase_id = $id";
  $res = mysqli_query($con, $sql);
  $ro = mysqli_fetch_assoc($res); 
  $balance = $ro['balance'];
  $gtotal = $ro['gtotal'];


  $pstatus = "";

  if($amount < $balance ){ $pay_status = 'Partial payment'; }
  if($amount == 0 ){ $balance = 'Not paid'; }
  if($amount == $balance ){ $pay_status = 'Paid'; }

  $balance = $gtotal - $amount;

  if($amount < $balance ){
    $stmt = "UPDATE purchase SET payment_status = '$pay_status', balance = balance - $amount, 
    paid_amount = paid_amount + $amount WHERE  purchase_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
      $sql = "INSERT INTO pur_payments (pur_id, amount, account, date, created_by, description) 
      VALUES ($id,$amount,$pmeth,'$date', '$uid', '$des')";
      $re = mysqli_query($con, $sql);
      echo "updated";
    }
  }else{
    $stmt = "UPDATE purchase SET payment_status = '$pay_status', balance = balance - $amount, 
    paid_amount = $amount WHERE  purchase_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
      $sql = "INSERT INTO pur_payments (pur_id, amount, account, date, created_by, description) 
      VALUES ($id,$amount,$pmeth,'$date', '$uid', '$des')";
      $re = mysqli_query($con, $sql);
      echo "updated";
    }
  }

 


}

?>
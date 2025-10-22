<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['viewSingleSale'])){
    $id = $_POST['viewSingleSale'];

    $stmt = "SELECT orders.order_id, orders.ser,orders.po_number, orders.payment_deadline,orders.trans_date ,users.fullname,orders.order_date, customer.cust_name, 
    customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis,orders.discount_on_all, orders.pr_be_dis ,orders.balance, orders.payment_status 
    FROM orders, users,customer WHERE orders.cust_id = customer.customer_id AND orders.order_by = users.userid AND orders.order_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $id = $row['order_id'];
        $ser = $row['ser'];
        $odate = date('d-m-Y @ h:i:a', strtotime($row['order_date'])) ;
        $tdate = date('d-m-Y', strtotime($row['trans_date'])) ;
        $cust = $row['cust_name'];
        $cphone = $row['cust_phone'];
        $ostatus = $row['order_status'];
        $pamount = $row['amount'];
        $balance = $row['balance'];
        $pstatus = $row['payment_status'];
        $username = $row['fullname'];
        $tamount = $row['pr_af_dis'];
        $discount = $row['discount_on_all'];
        $po = $row['po_number'];
        $duedate = $row['payment_deadline'];


        echo "

        <div class='col-md-4'>
        <p><strong>Order date:</strong></p>
        <p>$odate</p>
        </div>
      <div class='col-md-4'>
        <p><strong>Invoice number:</strong></p>
        <p>$ser</p>
      </div>
      <div class='col-md-4'>
        <p><strong>Purchase order (PO):</strong></p>
        <p>$po</p>
      </div>
      
      <div class='col-md-4'>
      <p><strong>Due date:</strong></p>
      <p>$duedate</p>
    </div>
      <div class='col-md-4'>
        <p><strong>Customer:</strong></p>
        <p>$cust</p>
      </div>
      <hr>
      <div class='col-md-4'>
        <p><strong>Order status:</strong></p>
        <p><span class='badge  badge-danger'>$ostatus</span></p>
      </div>
      <div class='col-md-4'>
        <p><strong>Payment status:</strong></p>
        <p><span class='badge  badge-info'>$pstatus</span></p>
      </div>
      <div class='col-md-4'>
        <p><strong>Order Taken by:</strong></p>
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
            <a class='nav-link active' id='home-tab' data-toggle='tab' href='#home' role='tab' aria-controls='home' aria-selected='true'>Order items</a>
            </li>
            <li class='nav-item' role='presentation'>
            <a class='nav-link' id='profile-tab' data-toggle='tab' href='#profile' role='tab' aria-controls='profile' aria-selected='false'>Payments</a>
            </li>
        </ul> 
        <div class='tab-content' id='myTabContent'>
        ";

        $stmt = "SELECT orders.order_id, order_item.order_item_id, order_item.qty, order_item.discount, order_item.price, order_item.sub_total, item.item_name, item.item_image FROM orders, order_item, item 
        WHERE orders.order_id = order_item.order_id AND order_item.item_id = item.item_id AND order_item.order_id = $id";
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
    $stmt = "SELECT payment.*, account.account_name, account.account_number FROM payment, account WHERE payment.account = account.account_id AND payment.order_id = $id";
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
  $stmt = "SELECT balance FROM orders WHERE order_id = $id";
  $result = mysqli_query($con, $stmt);
  while($row = mysqli_fetch_array($result)){
    echo $row[0];
  }
}

if(isset($_POST['btnDelSale'])){
  $id = $_POST['btnDelSale'];

  $stmt = "DELETE FROM orders WHERE order_id = $id";
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
  }else{
    $stmt = "UPDATE orders SET payment_status = '$pay_status', balance = balance - $amount, 
    amount = $amount WHERE order_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
      $sql = "INSERT INTO payment (order_id, amount, account, date, created_by, description) 
      VALUES ($id,$amount,$pmeth,'$date', '$uid', '$des')";
      $re = mysqli_query($con, $sql);
      echo "updated";
    }
  }


 


}



?>
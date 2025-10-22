<?php 

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');



//Making order 
if(isset($_POST['make_order'])){
    $date = date('Y-m-d h:i:s a');
    $id = $_SESSION['uid'];

    $stmt = "DELETE FROM orders WHERE order_status='on-going' AND order_by = $id";
    $result = mysqli_query($con, $stmt);

    $stmt = "INSERT INTO orders (cust_id, order_status, order_by, trans_date, warehouse) ";
    $stmt .= "VALUES ( 29, 'on-going', $id, '$date', 1)";
    $result = mysqli_query($con, $stmt); 
    if($result){
        $last_id = mysqli_insert_id($con);
        echo $last_id;
        $_SESSION['order_id'] = $last_id;
    }
}

/// Getting customers
if(isset($_POST['get_customer'])){
    $phone = $_POST['get_customer'];

    $sql = "SELECT * FROM customer WHERE cust_phone = '$phone'";
    $res = mysqli_query($con, $sql);
    while($row = mysqli_fetch_assoc($res)){
        $id = $row['customer_id'];
        $name = $row['cust_name'];
        $phone = $row['cust_phone'];
        echo "
          <option data-tokens='$phone' value='$id'>$name  </option>
        ";
    }

    $sql = "SELECT * FROM customer WHERE cust_phone != '$phone'";
    $res = mysqli_query($con, $sql);
    while($row = mysqli_fetch_assoc($res)){
        $id = $row['customer_id'];
        $name = $row['cust_name'];
        $phone = $row['cust_phone'];
        echo "
          <option data-tokens='$phone' value='$id'>$name  </option>
        ";
    }
}


if(isset($_POST['product_sear'])){
  $val = $_POST['product_sear'];

  $stmt = "SELECT * FROM item WHERE item_name  LIKE '%$val%' LIMIT 5";
  $result = mysqli_query($con, $stmt);
  while($row = mysqli_fetch_assoc($result)){
      $name = $row['item_name'];
      $id = $row['item_id'];

      echo "
      <li class='list-group-item contsearch'>
          <a href='javascript:void(0)'  class='gsearch' style='color:#333;text-decoration:none;' data-id='$id'>$name</a>
      </li>
      ";
  }
}

if(isset($_POST['addPro'])){
    $id = $_POST['addPro'];
    $uid = $_SESSION['uid'];

    $oder = $_POST['orderid'];

    //Getting product price 
    $stmt = "SELECT * FROM item WHERE item_id = $id";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_assoc($result); 
    $price = $row['sale_price'];

    $stmt = "SELECT * FROM order_item WHERE order_id = $oder AND item_id = $id";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) > 0){
        $stmt = "UPDATE order_item SET qty = qty+1, sub_total = price * qty";
        $result = mysqli_query($con, $stmt);
        echo 'success';
    }else{
        $stmt = "INSERT INTO order_item(order_id,item_id, qty, price, sub_total)";
        $stmt .= " VALUES($oder, $id, 1, $price, qty*price) ";
        $result = mysqli_query($con, $stmt);
        if($result){
            $stmt = "UPDATE orders SET balance = pr_af_dis - amount WHERE order_id = $id";
            $result = mysqli_query($con, $stmt);
            if($result){

                echo "success";
            }
        }
    }
}


//Getting items on cart
if(isset($_POST['pro_on_cart'])){

    //Getting order id
    $oder = $_POST['orderid'];


    $stmt = "SELECT item_name, sale_price, order_item.qty,order_item.discount, order_item.sub_total, order_item_id  ";
    $stmt .= " FROM item, order_item WHERE item.item_id = order_item.item_id AND order_item.order_id = $oder ";
    $result = mysqli_query($con, $stmt );
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $pname = $row['item_name'];
            $price = $row['sale_price'];
            $qty = $row['qty'];
            $dis = $row['discount'];
            $subtotal = $row['sub_total'];
            $oiid = $row['order_item_id'];
    
            echo "
            <tr>
                <td ><input type='text' name='' id='' value='$pname' class='form-control' readonly> </td>
                <td>
                    <div class='input-group '>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-danger btn-flat' id='btnMinus' data-id='$oiid'> <i class='fa fa-minus'></i> </button>
                        </span>
                        <input type='number' class='form-control rounded-0'  id='qty' value='$qty' data-id='$oiid'>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-info btn-flat' id='btnPlus' data-id='$oiid' > <i class='fa fa-plus'></i> </button>
                        </span>
                    </div>
                </td>
                <td> <input type='number' name='' steps='0.2' value='$price' id='' class='form-control' readonly> </td>
                <td><input type='number' name=''   steps='0.2' value='$dis'  id='disc' data-id='$oiid 'class='form-control' ></td>
                <td><input type='number' name='' steps='0.2' value='$subtotal' id='' class='form-control' readonly ></td>
                <td>
                    <button class='btn btn-danger' type='button' id='rm_pro' data-id='$oiid'> <i class='fa fa-trash'></i> </button>
                </td>
            </tr>
            ";
        }

    }else{
        echo "
        <tr>
            <td colspan='6'> <p class='text-center'><strong>No item added on the list</strong></p> </td>
        </tr>
        ";
    }
}

// Getting total price of all items
if(isset($_POST['total_pro'])){
    
    $oder = $_POST['orderid'];


    $stmt = "SELECT SUM(sub_total)  FROM order_item WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($result){
        $row = mysqli_fetch_array($result);
        if ( $row[0] == 0){
            echo "0.00";
        }else{
            echo number_format($row[0], 2, '.', ',')  . " $";
        }
    }else{
        echo "0.00";
    }

}


// REmoving items
if(isset($_POST['remove_pro'])){
    $id = $_POST['remove_pro'];
    $stmt = "DELETE FROM order_item WHERE order_item_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo "deleted";
    }
}

// Quantity on keyup
if(isset($_POST['upd_qty'])){
    $id = $_POST['upd_qty'];
    $qty = $_POST['qty'];



    $stm = "SELECT item.qty from item WHERE item_id in 
    (SELECT item_id FROM order_item WHERE order_item_id = $id) ";
    $res = mysqli_query($con, $stm);
    $pro_id = mysqli_fetch_assoc($res);
    $qtty = $pro_id['qty'];


    $s = "SELECT * FROM order_item WHERE order_item_id = $id";
    $r = mysqli_query($con, $s);
    $quantity = mysqli_fetch_assoc($r);

    if( $pro_id['qty']  < $qty ){
        echo 'quantity_exceeded';
        $sql = "UPDATE order_item SET qty = $qtty, sub_total = qty*price WHERE order_item_id = $id ";
        $exec = mysqli_query($con, $sql);
    }  else{
        
        $stmt = "UPDATE order_item SET qty = $qty, sub_total = qty*price WHERE order_item_id = $id ";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }
    }

}

// Incrementing Qauntity
if(isset($_POST['inc_qty'])){

    $id = $_POST['inc_qty'];

    $stm = "SELECT item.qty from item WHERE item_id in 
    (SELECT item_id FROM order_item WHERE order_item_id = $id) ";
    $res = mysqli_query($con, $stm);
    $pro_id = mysqli_fetch_assoc($res);

    $s = "SELECT * FROM order_item WHERE order_item_id = $id";
    $r = mysqli_query($con, $s);
    $quantity = mysqli_fetch_assoc($r);

    if($pro_id['qty'] == $quantity['qty'] || $pro_id['qty'] <= $quantity['qty']  ){
        echo 'quantity_exceeded';
        
    }else{
        $stmt = "UPDATE order_item SET qty = qty + 1, sub_total = sub_total + price WHERE order_item_id = $id ";
        $result = mysqli_query($con, $stmt);
        if($result){
           echo 'success';
            
        }
    }


}

//Decrementing quantity
if(isset($_POST['oiid'])){

    $id = $_POST['oiid'];
    
    $stmt = "UPDATE order_item SET qty = qty - 1, sub_total = sub_total - price WHERE order_item_id = $id ";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo 'success';
        
    }

}


//DIscount on item
if(isset($_POST['disc_upd'])){
    $id = $_POST['disc_upd'];
    $val = $_POST['val_des'];

    
        $stmt = "UPDATE order_item SET discount ='$val', sub_total = qty * price , sub_total = sub_total - discount  WHERE order_item_id = $id ";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'updated';
        }
    
}

//Grand Total
if(isset($_POST['grand_total'])){

    $oder = $_POST['orderid'];
    $total = number_format($_POST['grand_total'], 2);

    if($total == ""){ $total = 0.00; }

    $stmt = "SELECT SUM(sub_total)  FROM order_item WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($result){
        $row = mysqli_fetch_array($result);
        
        if ( $row[0] == 0){
            echo "0.00";
        }else{
            $res = $row[0] - $total;
            echo number_format($res, 2) . " $";
            //echo $row[0]  - $total ;
        }
    }else{
        echo "0.00";
    }
}


//Paid Amount

if(isset($_POST['balance'])){

    $oder = $_POST['balance'];   

    $stmt = "SELECT balance  FROM orders WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($result){
        $row = mysqli_fetch_array($result);
        if ( $row[0] == 0){
            echo $row[0];
        }else{

            echo $row[0];
        }
    }else{
        echo "0.00";
    }
}

if(isset($_POST['paid_amount'])){

    $amount = $_POST['paid_amount'];   
    $oder = $_POST['orderid'];   
    if(empty($amount)){ $amount = 0; }

    $stmt = "SELECT balance  FROM orders WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($result){
        $row = mysqli_fetch_array($result);
        if ( $row[0] == 0){
            echo $row[0];
        }else{

            echo $row[0] - $amount;
        }
    }else{
        echo "0.00";
    }
}


 // Check partial payment for walking customer 
 if(isset($_POST['check_partial_py'])){
    $pamount = $_POST['check_partial_py'];
    $des = $_POST['des_partial'];

    $oder = $_POST['order_id'];

    $stmt = "SELECT SUM(sub_total)  FROM order_item WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($row = mysqli_fetch_array($result)){
       
        echo $row[0] - $des;
    }
}

//Saving Order
if(isset($_POST['update_order_final'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid'])){
        echo "session_expired";
        exit;
    }
    
    $cid = mysqli_real_escape_string($con,  $_POST['update_order_final'] );
    $sdate = mysqli_real_escape_string($con, $_POST['sdate']);
    $id = $_SESSION['uid'];
    $oder = intval($_POST['order_id']);

    $ptype = mysqli_real_escape_string($con, $_POST['ptype']);
    $desc = floatval($_POST['discount_on_all']);
    $paid_amount = floatval($_POST['paid_amount_order']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $sdate = mysqli_real_escape_string($con, $_POST['sdate']);
    $date = date('Y-m-d h:i:s a');

    if(empty($desc)){ $desc = 0; }
    if(empty($paid_amount)){ $paid_amount = 0; }

    $s = "SELECT * FROM orders WHERE order_id = $oder";
    $ex = mysqli_query($con, $s);
    $rw = mysqli_fetch_assoc($ex);
    $p_a_d = $rw['pr_af_dis'];
    $cpay_status = $rw['payment_status'];
    $curr_balance = $rw['balance'];
    
    $sql = "SELECT SUM(sub_total) FROM order_item WHERE order_id = $oder ";
    $res = mysqli_query($con, $sql);
    $rows = mysqli_fetch_array($res);

    $price_before_des = $rows[0];
    $price_after_des = $rows[0] - $desc;

    $balance = $price_after_des - $paid_amount;

    $pay_status = $cpay_status;

    if(empty($paid_amount) ){
        $pay_status = $cpay_status;
    }else if ($paid_amount == 0){ $pay_status = 'Not paid'; } else if($paid_amount == $curr_balance){
        $pay_status = $cpay_status;

    } else if($paid_amount < $price_after_des ){ $pay_status = 'Partial payment';   }
    else if( $paid_amount == $price_after_des ) { $pay_status = 'Paid';}

    // if($paid_amount < $price_after_des ){ $pay_status = 'Partial payment'; }
    // if($paid_amount == 0 ){ $pay_status = 'Not paid'; }
    // if($paid_amount == $price_after_des ){ $pay_status = 'Paid'; }


    $sql = "UPDATE orders SET cust_id = $cid, order_status='$status', discount_on_all = $desc, pr_be_dis = $price_before_des, amount = $paid_amount,  ";
    $sql .= "pr_af_dis = $price_after_des, balance = $balance, order_date = '$sdate', trans_date= '$date' ,payment_status = '$pay_status' WHERE order_id = $oder ";
    $resu = mysqli_query($con, $sql); 
    if($resu){
        $date = date('Y-m-d h:i:s a');
        $s = "UPDATE   payment SET   amount = '$paid_amount', account = '$ptype' WHERE order_id = $oder ";
        $r = mysqli_query($con,$s); 
        
        unset($_SESSION['order_id']);

    

        

        //Inserting new order to the db
        // $stmt = "INSERT INTO orders (cid, status, created_by, order_date) ";
        // $stmt .= "VALUES ( 7, 'on-going', $id, '$date')";
        // $result = mysqli_query($con, $stmt); 
        // if($result){
        //    // echo 'success';
        //     $last_id = mysqli_insert_id($con);
        //     $_SESSION['order_id'] = $last_id;
        // }

        echo 'success';
    }

    
    



}






?>
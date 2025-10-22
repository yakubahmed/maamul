<?php 

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

// Initialize session cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

//Invoice number
// if(isset($_POST['get_invoice_num'])){
//     $stmt = "SELECT COUNT(*) FROM orders WHERE order_status != 'on-going'";
//     $result = mysqli_query($con, $stmt);
//     $row = mysqli_fetch_array($result);
    
//     $index = $row[0] + 1;
//     $prefix = 'SL-';
//     echo sprintf("%s%03s", $prefix, $index);
    

// }




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
    } else {
        echo "order_creation_failed: " . mysqli_error($con);
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
    
    // Debug: Check if order ID is valid
    if(empty($oder) || !is_numeric($oder)){
        echo "invalid_order_id";
        exit;
    }

    //Getting product price 
    $stmt = "SELECT * FROM item WHERE item_id = $id";
    $result = mysqli_query($con, $stmt); 
    if(!$result || mysqli_num_rows($result) == 0){
        echo "item_not_found";
        exit;
    }
    
    $row = mysqli_fetch_assoc($result); 
    $price = $row['sale_price'];
    $pprice = $row['pur_price'];

    $stmt = "SELECT * FROM order_item WHERE order_id = $oder AND item_id = $id";
    $result = mysqli_query($con, $stmt);
    
    // Check if query was successful
    if (!$result) {
        echo 'query_failed: ' . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($result) > 0){
        $stmt = "UPDATE order_item SET qty = qty+1, sub_total = price * qty - discount WHERE order_id = $oder AND item_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        } else {
            echo 'update_failed: ' . mysqli_error($con);
        }
    }else{
        $stmt = "INSERT INTO order_item(order_id,item_id, qty, price, sub_total, pprice)";
        $stmt .= " VALUES($oder, $id, 1, $price, $price, $pprice) ";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        } else {
            echo "insert_failed: " . mysqli_error($con);
        }
    }
}

//Sale price update 
if(isset($_POST['saleprice'])){
    $sprice = $_POST['saleprice'];
    $id = $_POST['salepid'];

    $stm = "SELECT pur_price FROM item WHERE item_id IN (select item_id FROM order_item WHERE order_item_id = $id) ";
    $res = mysqli_query($con, $stm); 
    $row = mysqli_fetch_array($res);
    $pprice = $row[0];

    if($sprice < $pprice){
        echo "Price";
    }else{
        $stmt =  "UPDATE order_item SET price = '$sprice', sub_total = price*qty WHERE order_item_id = '$id'";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        }

    }
    

 




}

//Getting items on cart
if(isset($_POST['pro_on_cart'])){

    //Getting order id
    $oder = $_POST['orderid'];


    $stmt = "SELECT item_name, order_item.price, order_item.qty,order_item.discount, order_item.sub_total, order_item_id  ";
    $stmt .= " FROM item, order_item WHERE item.item_id = order_item.item_id AND order_item.order_id = $oder ";
    $result = mysqli_query($con, $stmt );
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $pname = $row['item_name'];
            $price = $row['price'];
            $qty = $row['qty'];
            $dis = $row['discount'];
            $subtotal = $row['sub_total'];
            $oiid = $row['order_item_id'];

            if(empty($dis)){ $dis = 0;}
    
            echo "
            <tr>
                <td ><input type='text' name='' id='' value='$pname' class='form-control' readonly> </td>
                <td>
                    <div class='input-group '>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-danger btn-flat' id='btnMinus' data-id='$oiid'> <i class='fa fa-minus'></i> </button>
                        </span>
                        <input type='number' class='form-control rounded-0'  id='qty' value='$qty' data-id='$oiid' step='0.01'>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-info btn-flat' id='btnPlus' data-id='$oiid' > <i class='fa fa-plus'></i> </button>
                        </span>
                    </div>
                </td>
                <td> <input type='number' steps='0.2' value='$price' id='price' name='price' data-id='$oiid' class='form-control' > </td>
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


        
        $stmt = "UPDATE order_item SET qty = $qty, sub_total = qty*price WHERE order_item_id = $id ";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
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

if(isset($_POST['paid_amount'])){

    $oder = $_POST['orderid'];
    if (!empty($_POST['grand_total'])){ $total = $_POST['grand_total'];}
    else{
        $total = 0;
    }
    $paid_amount = $_POST['paid_amount'];

    if(empty($paid_amount)){$paid_amount = 0;}
    

    $stmt = "SELECT SUM(sub_total)  FROM order_item WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    if($result){
        $row = mysqli_fetch_array($result);
        if ( $row[0] == 0){
            echo $row[0];
        }else{
            echo $row[0] - $total - $paid_amount;
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
    $cid = mysqli_real_escape_string($con,  $_POST['update_order_final'] );
    $sdate = mysqli_real_escape_string($con, $_POST['sdate']);
    $id = $_SESSION['uid'];
    $oder = $_POST['order_id'];

    $s = "SELECT COUNT(*) FROM orders WHERE order_status != 'on-going'";
    $exec = mysqli_query($con, $s);
    $res = mysqli_fetch_array($exec);

    $index = $oder;
    $prefix = 'INV-';
    $serial =  sprintf("%s%04d", $prefix, $index);

    $ptype = $_POST['ptype'];
    $desc = $_POST['discount_on_all'];
    $paid_amount = $_POST['paid_amount_order'];
    $status = $_POST['status'];
    $sdate = $_POST['sdate'];
    $pdate = $_POST['pdateline'];
    $porder = $_POST['porder'];
    
    $date = date('Y-m-d h:i:s a');

    if(empty($paid_amount)){ $paid_amount = 0;  }


    
    $stmt = "SELECT SUM(sub_total)  FROM order_item WHERE order_id = $oder ";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    $price_before_des = $row[0];
    $price_after_des = $row[0] - $desc;

    $balance = $price_after_des - $paid_amount;

    if($paid_amount < $price_after_des ){ $pay_status = 'Partial payment'; }
    if($paid_amount == 0 ){ $pay_status = 'Not paid'; }
    if($paid_amount == $price_after_des ){ $pay_status = 'Paid'; }


    $sql = "UPDATE orders SET cust_id = $cid,po_number = '$porder' ,payment_deadline = '$pdate',ser = '$serial', order_status='$status', discount_on_all = $desc, pr_be_dis = $price_before_des, amount = $paid_amount,  ";
    $sql .= "pr_af_dis = $price_after_des, balance = $balance, order_date = '$sdate', trans_date= '$date' ,payment_status = '$pay_status' WHERE order_id = $oder ";
    $resu = mysqli_query($con, $sql); 
    if($resu){
        $date = date('Y-m-d h:i:s a');
        
        unset($_SESSION['order_id']);

        if($paid_amount != 0){
            $stmt = "INSERT INTO  payment (order_id, amount, account, date, created_by) ";
            $stmt .= "VALUES ($oder, $paid_amount, $ptype, '$date', $id)";
            $result = mysqli_query($con,$stmt); 

        }

        //Inserting new order to the db
        // $stmt = "INSERT INTO orders (cid, status, created_by, order_date) ";
        // $stmt .= "VALUES ( 7, 'on-going', $id, '$date')";
        // $result = mysqli_query($con, $stmt); 
        // if($result){
        //    // echo 'success';
        //     $last_id = mysqli_insert_id($con);
        //     $_SESSION['order_id'] = $last_id;
        // }
        
                $sq = "SELECT * FROM order_item WHERE order_id = $oder";
        $rs = mysqli_query($con, $sq);
        while($rww = mysqli_fetch_assoc($rs)){
            $item_id = $rww['item_id'];
            $q = $rww['qty'];


            
            $stmt = "UPDATE item SET qty = qty - $q WHERE item_id = $item_id ";
            $result = mysqli_query($con, $stmt);
           
    
        }

        echo 'success';
    }else{
        echo "failed";
    }

}






?>
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
    // Check if user is logged in
    if(!isset($_SESSION['uid'])){
        echo "session_expired";
        exit;
    }
    
    $id = intval($_POST['addPro']);
    $uid = $_SESSION['uid'];
    $oder = intval($_POST['orderid']);
    
    // Validate inputs
    if(empty($id) || empty($oder)){
        echo "invalid_input";
        exit;
    }

    //Getting product price 
    $stmt = "SELECT sale_price FROM item WHERE item_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($result) == 0){
        echo "item_not_found";
        exit;
    }
    
    $row = mysqli_fetch_assoc($result); 
    $price = $row['sale_price'];

    // Check if item already exists in quotation
    $stmt = "SELECT * FROM quotation_item WHERE quotation_id = $oder AND item_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($result) > 0){
        // Update existing item quantity
        $stmt = "UPDATE quotation_item SET qty = qty+1, sub_total = price * qty WHERE quotation_id = $oder AND item_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }else{
            echo "error: " . mysqli_error($con);
        }
    }else{
        // Insert new item
        $subtotal = $price * 1; // qty = 1 for new items
        $stmt = "INSERT INTO quotation_item(quotation_id, item_id, qty, price, sub_total)";
        $stmt .= " VALUES($oder, $id, 1, $price, $subtotal)";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo "success";
        }else{
            echo "error: " . mysqli_error($con);
        }
    }
}


//Getting items on cart
if(isset($_POST['pro_on_cart'])){

    //Getting order id
    $oder = $_POST['orderid'];

    $stmt = "SELECT item_name, sale_price, quotation_item.qty,quotation_item.discount, quotation_item.sub_total, qitem_id";
    $stmt .= " FROM item, quotation_item WHERE item.item_id = quotation_item.item_id AND quotation_item.quotation_id = $oder  ";
    $result = mysqli_query($con, $stmt );
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $pname = $row['item_name'];
            $price = $row['sale_price'];
            $qty = $row['qty'];
            $dis = $row['discount'];
            $subtotal = $row['sub_total'];
            $oiid = $row['qitem_id'];

            if(empty($dis)){$dis =0; }
    
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
    $oder = intval($_POST['orderid']);
    
    if(empty($oder)){
        echo "0.00 $";
        exit;
    }

    $stmt = "SELECT SUM(sub_total) FROM quotation_item WHERE quotation_id = $oder";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        error_log("Total calculation error: " . mysqli_error($con));
        echo "0.00 $";
        exit;
    }
    
    $row = mysqli_fetch_array($result);
    $total = $row[0];
    
    if(empty($total) || $total == 0 || $total == null){
        echo "0.00 $";
    }else{
        echo number_format($total, 2, '.', ',') . " $";
    }
}


// REmoving items
if(isset($_POST['remove_pro'])){
    $id = intval($_POST['remove_pro']);
    
    if(empty($id)){
        echo "invalid_input";
        exit;
    }
    
    $stmt = "DELETE FROM quotation_item WHERE qitem_id = $id";
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

// Quantity on keyup
if(isset($_POST['upd_qty'])){
    $id = intval($_POST['upd_qty']);
    $qty = intval($_POST['qty']);
    
    if(empty($id) || $qty < 1){
        echo "invalid_input";
        exit;
    }

    $stm = "SELECT item.qty from item WHERE item_id in 
    (SELECT item_id FROM quotation_item WHERE qitem_id = $id)";
    $res = mysqli_query($con, $stm);
    
    if(!$res || mysqli_num_rows($res) == 0){
        echo "item_not_found";
        exit;
    }
    
    $pro_id = mysqli_fetch_assoc($res);
    $qtty = $pro_id['qty'];

    if($pro_id['qty'] < $qty){
        echo 'quantity_exceeded';
        $sql = "UPDATE quotation_item SET qty = $qtty, sub_total = $qtty*price - discount WHERE qitem_id = $id";
        $exec = mysqli_query($con, $sql);
    } else{
        $stmt = "UPDATE quotation_item SET qty = $qty, sub_total = $qty*price - discount WHERE qitem_id = $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo "error: " . mysqli_error($con);
            exit;
        }
        
        if($result){
           echo 'success';
        }else{
            echo "failed";
        }
    }

}

// Incrementing Qauntity
if(isset($_POST['inc_qty'])){
    $id = intval($_POST['inc_qty']);
    
    if(empty($id)){
        echo "invalid_input";
        exit;
    }

    $stm = "SELECT item.qty from item WHERE item_id in 
    (SELECT item_id FROM quotation_item WHERE qitem_id = $id)";
    $res = mysqli_query($con, $stm);
    
    if(!$res || mysqli_num_rows($res) == 0){
        echo "item_not_found";
        exit;
    }
    
    $pro_id = mysqli_fetch_assoc($res);

    $s = "SELECT * FROM quotation_item WHERE qitem_id = $id";
    $r = mysqli_query($con, $s);
    
    if(!$r || mysqli_num_rows($r) == 0){
        echo "item_not_found";
        exit;
    }
    
    $quantity = mysqli_fetch_assoc($r);

    if($pro_id['qty'] <= $quantity['qty']){
        echo 'quantity_exceeded';
    }else{
        // Get current values
        $current_qty = $quantity['qty'];
        $current_price = $quantity['price'];
        $current_discount = isset($quantity['discount']) ? $quantity['discount'] : 0;
        
        // Calculate new values
        $new_qty = $current_qty + 1;
        $new_subtotal = ($new_qty * $current_price) - $current_discount;
        
        $stmt = "UPDATE quotation_item SET qty = $new_qty, sub_total = $new_subtotal WHERE qitem_id = $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo "error: " . mysqli_error($con);
            exit;
        }
        
        if($result){
           echo 'success';
        }else{
            echo "failed";
        }
    }
}

//Decrementing quantity
if(isset($_POST['oiid'])){
    $id = intval($_POST['oiid']);
    
    if(empty($id)){
        echo "invalid_input";
        exit;
    }
    
    // Get current item data
    $check = "SELECT qty, price, discount FROM quotation_item WHERE qitem_id = $id";
    $check_res = mysqli_query($con, $check);
    
    if(!$check_res || mysqli_num_rows($check_res) == 0){
        echo "item_not_found";
        exit;
    }
    
    $item_data = mysqli_fetch_assoc($check_res);
    
    if($item_data['qty'] <= 1){
        echo "min_quantity";
        exit;
    }
    
    // Calculate new values
    $current_qty = $item_data['qty'];
    $current_price = $item_data['price'];
    $current_discount = isset($item_data['discount']) ? $item_data['discount'] : 0;
    
    $new_qty = $current_qty - 1;
    $new_subtotal = ($new_qty * $current_price) - $current_discount;
    
    $stmt = "UPDATE quotation_item SET qty = $new_qty, sub_total = $new_subtotal WHERE qitem_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "error: " . mysqli_error($con);
        exit;
    }
    
    if($result){
        echo 'success';
    }else{
        echo "failed";
    }
}


//DIscount on item
if(isset($_POST['disc_upd'])){
    $id = $_POST['disc_upd'];
    $val = $_POST['val_des'];

    
        $stmt = "UPDATE quotation_item SET discount ='$val', sub_total = qty * price , sub_total = sub_total - discount  WHERE qitem_id  = $id ";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'updated';
        }
    
}

//Grand Total
if(isset($_POST['grand_total'])){
    $oder = intval($_POST['orderid']);
    $discount = floatval($_POST['grand_total']);

    if(empty($oder)){
        echo "0.00 $";
        exit;
    }

    if(empty($discount)){ 
        $discount = 0.00; 
    }

    $stmt = "SELECT SUM(sub_total) FROM quotation_item WHERE quotation_id = $oder";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        error_log("Grand total calculation error: " . mysqli_error($con));
        echo "0.00 $";
        exit;
    }
    
    $row = mysqli_fetch_array($result);
    $subtotal = $row[0];
    
    if(empty($subtotal) || $subtotal == 0 || $subtotal == null){
        echo "0.00 $";
    }else{
        $grand_total = $subtotal - $discount;
        echo number_format($grand_total, 2, '.', ',') . " $";
    }
}



//Saving Order
if(isset($_POST['update_order_final'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid'])){
        echo "session_expired";
        exit;
    }
    
    $cid = intval($_POST['update_order_final']);
    $id = $_SESSION['uid'];
    $oder = intval($_POST['order_id']);
    
    // Validate inputs
    if(empty($cid) || empty($oder)){
        echo "missing_fields";
        exit;
    }

    $desc = floatval($_POST['discount_on_all']);
    $qdate = mysqli_real_escape_string($con, $_POST['sdate']);
    $date = date('Y-m-d h:i:s a');
    
    if(empty($desc)){ $desc = 0; }



    //if(empty($paid_amount)){ $paid_amount = 0;  }


    // Check if quotation has items
    $stmt = "SELECT SUM(sub_total) FROM quotation_item WHERE quotation_id = $oder";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "error: " . mysqli_error($con);
        exit;
    }
    
    $row = mysqli_fetch_array($result);
    $price_before_des = $row[0];
    
    if(empty($price_before_des) || $price_before_des == 0){
        echo "no_items";
        exit;
    }
    
    $price_after_des = $price_before_des - $desc;

    $sql = "UPDATE quotation SET cust_id = $cid, discount = $desc, total = $price_before_des, ";
    $sql .= "grand_total = $price_after_des, status = 'active', date = '$qdate', created_date= '$date' WHERE qoutation_id = $oder";
    $resu = mysqli_query($con, $sql);
    
    if(!$resu){
        echo "error: " . mysqli_error($con);
        exit;
    }
    
    if($resu){
        unset($_SESSION['order_id']);
        echo 'success';
    }else{
        echo "failed_to_save";
    }

}





?>
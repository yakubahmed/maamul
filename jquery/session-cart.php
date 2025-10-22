<?php 
// Start output buffering to prevent any extra output
ob_start();

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

// Set content type to ensure clean responses
header('Content-Type: text/plain');

// Initialize session cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Session Cart Management
if(isset($_POST['addToSessionCart'])){
    $item_id = $_POST['addToSessionCart'];
    
    // Get item details
    $stmt = "SELECT * FROM item WHERE item_id = $item_id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result || mysqli_num_rows($result) == 0){
        ob_clean();
        echo "item_not_found";
        exit;
    }
    
    $row = mysqli_fetch_assoc($result);
    $item_name = $row['item_name'];
    $sale_price = $row['sale_price'];
    $purchase_price = $row['pur_price'];
    $stock_qty = $row['qty'];
    
    // Check if item already in cart
    if(isset($_SESSION['cart'][$item_id])){
        // Check stock availability
        if($_SESSION['cart'][$item_id]['qty'] >= $stock_qty){
            ob_clean();
            echo "quantity_exceeded";
            exit;
        }
        $_SESSION['cart'][$item_id]['qty']++;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$item_id] = array(
            'item_name' => $item_name,
            'sale_price' => $sale_price,
            'purchase_price' => $purchase_price,
            'qty' => 1,
            'discount' => 0
        );
    }
    
    // Clean output and return success
    ob_clean();
    echo "success";
    exit;
}

if(isset($_POST['loadSessionCart'])){
    if(empty($_SESSION['cart'])){
        echo "<tr><td colspan='6'><p class='text-center'><strong>No items in cart</strong></p></td></tr>";
    } else {
        foreach($_SESSION['cart'] as $item_id => $item){
            $subtotal = ($item['sale_price'] * $item['qty']) - $item['discount'];
            echo "
            <tr>
                <td><input type='text' name='' id='' value='{$item['item_name']}' class='form-control' readonly></td>
                <td>
                    <div class='input-group'>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-danger btn-flat' id='btnMinus' data-id='$item_id'><i class='fa fa-minus'></i></button>
                        </span>
                        <input type='number' class='form-control rounded-0' id='qty' value='{$item['qty']}' data-id='$item_id' step='0.01'>
                        <span class='input-group-append'>
                            <button type='button' class='btn btn-info btn-flat' id='btnPlus' data-id='$item_id'><i class='fa fa-plus'></i></button>
                        </span>
                    </div>
                </td>
                <td><input type='number' steps='0.2' value='{$item['sale_price']}' id='price' name='price' data-id='$item_id' class='form-control'></td>
                <td><input type='number' name='' steps='0.2' value='{$item['discount']}' id='disc' data-id='$item_id' class='form-control'></td>
                <td><input type='number' name='' steps='0.2' value='$subtotal' id='' class='form-control' readonly></td>
                <td>
                    <button class='btn btn-danger' type='button' id='rm_pro' data-id='$item_id'><i class='fa fa-trash'></i></button>
                </td>
            </tr>";
        }
    }
}

if(isset($_POST['updateSessionCartQty'])){
    $item_id = $_POST['updateSessionCartQty'];
    $qty = $_POST['qty'];
    
    if(isset($_SESSION['cart'][$item_id])){
        // Check stock availability
        $stmt = "SELECT qty FROM item WHERE item_id = $item_id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($result);
        $stock_qty = $row['qty'];
        
        if($qty > $stock_qty){
            ob_clean();
            echo "quantity_exceeded";
            exit;
        } else {
            $_SESSION['cart'][$item_id]['qty'] = $qty;
            ob_clean();
            echo "success";
            exit;
        }
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['incrementSessionCart'])){
    $item_id = $_POST['incrementSessionCart'];
    
    if(isset($_SESSION['cart'][$item_id])){
        // Check stock availability
        $stmt = "SELECT qty FROM item WHERE item_id = $item_id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($result);
        $stock_qty = $row['qty'];
        
        if($_SESSION['cart'][$item_id]['qty'] >= $stock_qty){
            ob_clean();
            echo "quantity_exceeded";
            exit;
        } else {
            $_SESSION['cart'][$item_id]['qty']++;
            ob_clean();
            echo "success";
            exit;
        }
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['decrementSessionCart'])){
    $item_id = $_POST['decrementSessionCart'];
    
    if(isset($_SESSION['cart'][$item_id])){
        if($_SESSION['cart'][$item_id]['qty'] > 1){
            $_SESSION['cart'][$item_id]['qty']--;
        }
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['removeFromSessionCart'])){
    $item_id = $_POST['removeFromSessionCart'];
    
    if(isset($_SESSION['cart'][$item_id])){
        unset($_SESSION['cart'][$item_id]);
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['updateSessionCartDiscount'])){
    $item_id = $_POST['updateSessionCartDiscount'];
    $discount = $_POST['discount'];
    
    if(isset($_SESSION['cart'][$item_id])){
        $_SESSION['cart'][$item_id]['discount'] = $discount;
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['updateSessionCartPrice'])){
    $item_id = $_POST['itemId'];
    $price = $_POST['updateSessionCartPrice'];
    
    if(isset($_SESSION['cart'][$item_id])){
        // Check if price is not lower than purchase price
        if($price < $_SESSION['cart'][$item_id]['purchase_price']){
            ob_clean();
            echo "price_too_low";
            exit;
        } else {
            $_SESSION['cart'][$item_id]['sale_price'] = $price;
            ob_clean();
            echo "success";
            exit;
        }
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['calculateSessionTotals'])){
    $subtotal = 0;
    $discount_total = 0;
    
    foreach($_SESSION['cart'] as $item){
        $item_total = ($item['sale_price'] * $item['qty']) - $item['discount'];
        $subtotal += $item_total;
        $discount_total += $item['discount'];
    }
    
    $grand_total = $subtotal;
    
    echo json_encode(array(
        'subtotal' => number_format($subtotal, 2) . ' $',
        'discount' => number_format($discount_total, 2) . ' $',
        'grandtotal' => number_format($grand_total, 2) . ' $'
    ));
}

if(isset($_POST['commitSessionCart'])){
    $customer = $_POST['customer'];
    $sale_date = $_POST['sale_date'];
    $status = $_POST['status'];
    $payment_method = $_POST['payment_method'];
    $paid_amount = floatval($_POST['paid_amount']);
    $discount_total = floatval($_POST['discount_total']);
    $due_date = $_POST['due_date'];
    $po_number = $_POST['po_number'];
    
    $date = date('Y-m-d h:i:s a');
    $user_id = $_SESSION['uid'];
    
    // Create order
    $stmt = "INSERT INTO orders (cust_id, order_status, order_by, trans_date, warehouse, ser, order_date, po_number, payment_deadline) ";
    $stmt .= "VALUES ($customer, '$status', $user_id, '$date', 1, 'TEMP', '$sale_date', '$po_number', '$due_date')";
    $result = mysqli_query($con, $stmt);
    
    if($result){
        $order_id = mysqli_insert_id($con);
        
        // Generate invoice number
        $prefix = 'INV-';
        $serial = sprintf("%s%04d", $prefix, $order_id);
        
        // Update order with invoice number
        $stmt = "UPDATE orders SET ser = '$serial' WHERE order_id = $order_id";
        mysqli_query($con, $stmt);
        
        // Add items to order_item table
        $total_amount = 0;
        foreach($_SESSION['cart'] as $item_id => $item){
            $subtotal = ($item['sale_price'] * $item['qty']) - $item['discount'];
            $total_amount += $subtotal;
            
            $stmt = "INSERT INTO order_item (order_id, item_id, qty, price, sub_total, pprice, discount) ";
            $stmt .= "VALUES ($order_id, $item_id, {$item['qty']}, {$item['sale_price']}, $subtotal, {$item['purchase_price']}, {$item['discount']})";
            mysqli_query($con, $stmt);
            
            // Update stock
            $stmt = "UPDATE item SET qty = qty - {$item['qty']} WHERE item_id = $item_id";
            mysqli_query($con, $stmt);
        }
        
        // Update order totals
        $final_total = $total_amount - $discount_total;
        $balance = $final_total - $paid_amount;
        
        $pay_status = 'Paid';
        if($paid_amount < $final_total) $pay_status = 'Partial payment';
        if($paid_amount == 0) $pay_status = 'Not paid';
        
        $stmt = "UPDATE orders SET pr_be_dis = $total_amount, pr_af_dis = $final_total, amount = $paid_amount, balance = $balance, payment_status = '$pay_status', discount_on_all = $discount_total WHERE order_id = $order_id";
        mysqli_query($con, $stmt);
        
        // Add payment if amount > 0
        if($paid_amount > 0){
            $stmt = "INSERT INTO payment (order_id, amount, account, date, created_by) ";
            $stmt .= "VALUES ($order_id, $paid_amount, $payment_method, '$date', $user_id)";
            mysqli_query($con, $stmt);
        }
        
        // Clear session cart
        $_SESSION['cart'] = array();
        
        echo "success";
    } else {
        echo "order_creation_failed: " . mysqli_error($con);
    }
}
?>

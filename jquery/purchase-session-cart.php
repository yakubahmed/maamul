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
if (!isset($_SESSION['purchase_cart'])) {
    $_SESSION['purchase_cart'] = array();
}

// Purchase Session Cart Management
if(isset($_POST['addToPurchaseCart'])){
    $item_id = $_POST['addToPurchaseCart'];
    
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
    $purchase_price = $row['pur_price'];
    $sale_price = $row['sale_price'];
    
    // Check if item already in cart
    if(isset($_SESSION['purchase_cart'][$item_id])){
        $_SESSION['purchase_cart'][$item_id]['qty']++;
    } else {
        // Add new item to cart
        $_SESSION['purchase_cart'][$item_id] = array(
            'item_name' => $item_name,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'qty' => 1,
            'discount' => 0
        );
    }
    
    // Clean output and return success
    ob_clean();
    echo "success";
    exit;
}

if(isset($_POST['loadPurchaseCart'])){
    if(empty($_SESSION['purchase_cart'])){
        echo "<tr><td colspan='6'><p class='text-center'><strong>No items in cart</strong></p></td></tr>";
    } else {
        foreach($_SESSION['purchase_cart'] as $item_id => $item){
            $subtotal = ($item['purchase_price'] * $item['qty']) - $item['discount'];
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
                <td><input type='number' steps='0.2' value='{$item['purchase_price']}' id='price' name='price' data-id='$item_id' class='form-control'></td>
                <td><input type='number' name='' steps='0.2' value='{$item['discount']}' id='disc' data-id='$item_id' class='form-control'></td>
                <td><input type='number' name='' steps='0.2' value='$subtotal' id='' class='form-control' readonly></td>
                <td>
                    <button class='btn btn-danger' type='button' id='rm_pro' data-id='$item_id'><i class='fa fa-trash'></i></button>
                </td>
            </tr>";
        }
    }
}

if(isset($_POST['updatePurchaseCartQty'])){
    $item_id = $_POST['updatePurchaseCartQty'];
    $qty = $_POST['qty'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        $_SESSION['purchase_cart'][$item_id]['qty'] = $qty;
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['incrementPurchaseCart'])){
    $item_id = $_POST['incrementPurchaseCart'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        $_SESSION['purchase_cart'][$item_id]['qty']++;
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['decrementPurchaseCart'])){
    $item_id = $_POST['decrementPurchaseCart'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        if($_SESSION['purchase_cart'][$item_id]['qty'] > 1){
            $_SESSION['purchase_cart'][$item_id]['qty']--;
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

if(isset($_POST['removeFromPurchaseCart'])){
    $item_id = $_POST['removeFromPurchaseCart'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        unset($_SESSION['purchase_cart'][$item_id]);
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['updatePurchaseCartDiscount'])){
    $item_id = $_POST['updatePurchaseCartDiscount'];
    $discount = $_POST['discount'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        $_SESSION['purchase_cart'][$item_id]['discount'] = $discount;
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['updatePurchaseCartPrice'])){
    $item_id = $_POST['itemId'];
    $price = $_POST['updatePurchaseCartPrice'];
    
    if(isset($_SESSION['purchase_cart'][$item_id])){
        $_SESSION['purchase_cart'][$item_id]['purchase_price'] = $price;
        ob_clean();
        echo "success";
        exit;
    } else {
        ob_clean();
        echo "item_not_found";
        exit;
    }
}

if(isset($_POST['calculatePurchaseTotals'])){
    $subtotal = 0;
    $discount_total = 0;
    
    if(isset($_SESSION['purchase_cart']) && !empty($_SESSION['purchase_cart'])){
        foreach($_SESSION['purchase_cart'] as $item){
            $item_total = ($item['purchase_price'] * $item['qty']) - $item['discount'];
            $subtotal += $item_total;
            $discount_total += $item['discount'];
        }
    }
    
    // Add global discount if set
    $global_discount = isset($_SESSION['purchase_global_discount']) ? floatval($_SESSION['purchase_global_discount']) : 0;
    $discount_total += $global_discount;
    
    $grand_total = $subtotal - $global_discount;
    
    // Don't allow negative grand total
    if($grand_total < 0){
        $grand_total = 0;
    }
    
    echo json_encode(array(
        'subtotal' => number_format($subtotal, 2),
        'discount' => number_format($discount_total, 2),
        'grandtotal' => number_format($grand_total, 2)
    ));
    exit;
}

// Update global discount
if(isset($_POST['updateGlobalDiscount'])){
    $discount = floatval($_POST['updateGlobalDiscount']);
    
    // Don't allow negative discount
    if($discount < 0){
        $discount = 0;
    }
    
    $_SESSION['purchase_global_discount'] = $discount;
    echo "success";
    exit;
}

if(isset($_POST['commitPurchaseCart'])){
    ob_clean(); // Clear any previous output
    
    // Validate required fields
    if(empty($_POST['supplier']) || empty($_POST['purchase_date']) || empty($_POST['status'])){
        echo json_encode(array('error' => 'missing_fields'));
        exit;
    }
    
    // Check if cart is empty
    if(empty($_SESSION['purchase_cart'])){
        echo json_encode(array('error' => 'empty_cart'));
        exit;
    }
    
    $supplier = intval($_POST['supplier']);
    $purchase_date = mysqli_real_escape_string($con, $_POST['purchase_date']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $payment_method = !empty($_POST['payment_method']) ? intval($_POST['payment_method']) : 0;
    $paid_amount = floatval($_POST['paid_amount']);
    $discount_total = floatval($_POST['discount_total']);
    $due_date = !empty($_POST['due_date']) ? mysqli_real_escape_string($con, $_POST['due_date']) : '';
    $po_number = !empty($_POST['po_number']) ? mysqli_real_escape_string($con, $_POST['po_number']) : '';
    
    $date = date('Y-m-d h:i:s a');
    $user_id = $_SESSION['uid'];
    
    // Create purchase order in purchase table
    $stmt = "INSERT INTO purchase (supp_id, pur_status, pur_by, trans_date, warehouse, pur_date) ";
    $stmt .= "VALUES ($supplier, '$status', $user_id, '$date', 1, '$purchase_date')";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo json_encode(array('error' => 'order_creation_failed', 'message' => mysqli_error($con)));
        exit;
    }
    
    $purchase_id = mysqli_insert_id($con);
    
    // Generate invoice number
    $prefix = 'PUR-';
    $serial = sprintf("%s%04d", $prefix, $purchase_id);
    
    // Update purchase with invoice number
    $stmt = "UPDATE purchase SET ser = '$serial' WHERE purchase_id = $purchase_id";
    mysqli_query($con, $stmt);
    
    // Add items to pur_items table
    $total_amount = 0;
    foreach($_SESSION['purchase_cart'] as $item_id => $item){
        $subtotal = ($item['purchase_price'] * $item['qty']) - $item['discount'];
        $total_amount += $subtotal;
        
        $stmt = "INSERT INTO pur_items (purchase_id, item_id, qty, price, sub_total) ";
        $stmt .= "VALUES ($purchase_id, $item_id, {$item['qty']}, {$item['purchase_price']}, $subtotal)";
        $item_result = mysqli_query($con, $stmt);
        
        if(!$item_result){
            echo json_encode(array('error' => 'item_insert_failed', 'message' => mysqli_error($con)));
            exit;
        }
        
        // Update stock - ADD to stock for purchases
        $stmt = "UPDATE item SET qty = qty + {$item['qty']}, pur_price = {$item['purchase_price']} WHERE item_id = $item_id";
        mysqli_query($con, $stmt);
    }
    
    // Update purchase totals
    $final_total = $total_amount - $discount_total;
    $balance = $final_total - $paid_amount;
    
    // Determine payment status
    $pay_status = 'Paid';
    if($paid_amount < $final_total) $pay_status = 'Partial payment';
    if($paid_amount == 0) $pay_status = 'Not paid';
    
    $stmt = "UPDATE purchase SET p_be_dis = $total_amount, gtotal = $final_total, paid_amount = $paid_amount, balance = $balance, payment_status = '$pay_status', discount_on_all = $discount_total WHERE purchase_id = $purchase_id";
    $update_result = mysqli_query($con, $stmt);
    
    if(!$update_result){
        echo json_encode(array('error' => 'order_update_failed', 'message' => mysqli_error($con)));
        exit;
    }
    
    // Add payment if amount > 0 and payment method selected
    if($paid_amount > 0 && $payment_method > 0){
        $stmt = "INSERT INTO pur_payments (pur_id, amount, account, date, created_by) ";
        $stmt .= "VALUES ($purchase_id, $paid_amount, $payment_method, '$date', $user_id)";
        $payment_result = mysqli_query($con, $stmt);
        
        if(!$payment_result){
            error_log("Payment insert failed: " . mysqli_error($con));
            // Don't fail the entire purchase, just log the error
        }
    }
    
    // Clear session cart and global discount
    $_SESSION['purchase_cart'] = array();
    unset($_SESSION['purchase_global_discount']);
    
    echo json_encode(array('success' => true, 'purchase_id' => $purchase_id, 'serial' => $serial));
    exit;
}
?>



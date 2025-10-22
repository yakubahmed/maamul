<?php
include('../../inc/session_config.php');
session_start();
include('../../path.php');
include(ROOT_PATH . '/inc/config.php');

// Get customer outstanding invoices
if(isset($_POST['get_customer_invoices'])){
    $customer_id = mysqli_real_escape_string($con, $_POST['customer_id']);
    
    $stmt = "SELECT o.order_id, o.ser as invoice_no, o.order_date, o.pr_af_dis as total_amount, 
                    o.amount as paid_amount, o.balance, o.payment_status
             FROM orders o
             WHERE o.cust_id = $customer_id AND o.balance > 0
             ORDER BY o.order_date ASC";
    
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $order_id = $row['order_id'];
            $invoice_no = $row['invoice_no'];
            $order_date = date('M d, Y', strtotime($row['order_date']));
            $total = number_format($row['total_amount'], 2);
            $paid = number_format($row['paid_amount'], 2);
            $balance = number_format($row['balance'], 2);
            $status = $row['payment_status'];
            
            $statusClass = 'warning';
            if($status == 'Paid') $statusClass = 'success';
            if($status == 'Not paid') $statusClass = 'danger';
            
            echo "
            <div class='card invoice-card mb-3'>
                <div class='card-body'>
                    <div class='row align-items-center'>
                        <div class='col-md-1'>
                            <div class='custom-control custom-checkbox'>
                                <input type='checkbox' class='custom-control-input invoice-checkbox' 
                                       id='invoice-{$order_id}' 
                                       data-invoice-id='{$order_id}' 
                                       data-balance='{$row['balance']}'>
                                <label class='custom-control-label' for='invoice-{$order_id}'></label>
                            </div>
                        </div>
                        <div class='col-md-3'>
                            <strong>{$invoice_no}</strong><br>
                            <small class='text-muted'>{$order_date}</small>
                        </div>
                        <div class='col-md-2'>
                            <small class='text-muted'>Total</small><br>
                            <strong>\${$total}</strong>
                        </div>
                        <div class='col-md-2'>
                            <small class='text-muted'>Paid</small><br>
                            <span class='text-success'>\${$paid}</span>
                        </div>
                        <div class='col-md-2'>
                            <small class='text-muted'>Balance</small><br>
                            <span class='text-danger'><strong>\${$balance}</strong></span>
                        </div>
                        <div class='col-md-2'>
                            <label class='mb-1'><small>Pay Amount</small></label>
                            <input type='number' 
                                   class='form-control form-control-sm payment-amount' 
                                   id='amount-{$order_id}'
                                   data-invoice-id='{$order_id}'
                                   data-balance='{$row['balance']}'
                                   step='0.01' 
                                   min='0' 
                                   max='{$row['balance']}'
                                   placeholder='0.00'
                                   disabled>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo "<div class='alert alert-info'>No outstanding invoices found for this customer.</div>";
    }
}

// Process payment
if(isset($_POST['process_payment'])){
    $customer_id = mysqli_real_escape_string($con, $_POST['customer_id']);
    $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
    $payment_date = mysqli_real_escape_string($con, $_POST['payment_date']);
    $payment_note = mysqli_real_escape_string($con, $_POST['payment_note']);
    $invoices = json_decode($_POST['invoices'], true);
    $user_id = $_SESSION['uid'];
    
    if(empty($invoices)){
        echo "no_invoices_selected";
        exit;
    }
    
    $errors = array();
    $processed_count = 0;
    
    foreach($invoices as $invoice_id => $invoice_data){
        $paying_amount = floatval($invoice_data['paying']);
        
        if($paying_amount <= 0){
            continue;
        }
        
        // Get current order details
        $stmt = "SELECT pr_af_dis, amount, balance FROM orders WHERE order_id = $invoice_id";
        $result = mysqli_query($con, $stmt);
        $order = mysqli_fetch_assoc($result);
        
        if(!$order){
            $errors[] = "Invoice #$invoice_id not found";
            continue;
        }
        
        // Calculate new amounts
        $new_paid_amount = floatval($order['amount']) + $paying_amount;
        $new_balance = floatval($order['pr_af_dis']) - $new_paid_amount;
        
        // Determine payment status
        $payment_status = 'Partial payment';
        if($new_balance <= 0){
            $payment_status = 'Paid';
            $new_balance = 0;
        } else if($new_paid_amount == 0){
            $payment_status = 'Not paid';
        }
        
        // Update order
        $stmt = "UPDATE orders 
                 SET amount = $new_paid_amount, 
                     balance = $new_balance, 
                     payment_status = '$payment_status' 
                 WHERE order_id = $invoice_id";
        
        if(!mysqli_query($con, $stmt)){
            $errors[] = "Failed to update invoice #$invoice_id: " . mysqli_error($con);
            continue;
        }
        
        // Check if payment table has note column
        $check_column = mysqli_query($con, "SHOW COLUMNS FROM payment LIKE 'note'");
        $has_note_column = mysqli_num_rows($check_column) > 0;
        
        // Insert payment record
        if($has_note_column && !empty($payment_note)){
            $stmt = "INSERT INTO payment (order_id, amount, account, date, created_by, note) 
                     VALUES ($invoice_id, $paying_amount, $payment_method, '$payment_date', $user_id, '$payment_note')";
        } else {
            $stmt = "INSERT INTO payment (order_id, amount, account, date, created_by) 
                     VALUES ($invoice_id, $paying_amount, $payment_method, '$payment_date', $user_id)";
        }
        
        if(!mysqli_query($con, $stmt)){
            $errors[] = "Failed to record payment for invoice #$invoice_id: " . mysqli_error($con);
            continue;
        }
        
        $processed_count++;
    }
    
    if($processed_count > 0 && empty($errors)){
        echo "success";
    } else if($processed_count > 0 && !empty($errors)){
        echo "partial_success: " . $processed_count . " of " . count($invoices) . " payments processed. Errors: " . implode(", ", $errors);
    } else {
        echo "payment_failed: " . implode(", ", $errors);
    }
}
?>


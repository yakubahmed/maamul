<?php
session_start();
include('../../path.php');
include(ROOT_PATH . '/inc/config.php');

if(isset($_POST['show_report'])){
    $fdate = mysqli_real_escape_string($con, $_POST['fdate']);
    $tdate = mysqli_real_escape_string($con, $_POST['tdate']);
    $customer = isset($_POST['customer']) ? intval($_POST['customer']) : 0;
    $account = isset($_POST['account']) ? intval($_POST['account']) : 0;

    // Build the query
    $where_conditions = array();
    $where_conditions[] = "DATE(payment.date) BETWEEN '$fdate' AND '$tdate'";
    
    if($customer > 0){
        $where_conditions[] = "orders.cust_id = $customer";
    }
    
    if($account > 0){
        $where_conditions[] = "payment.account = $account";
    }
    
    $where_clause = implode(' AND ', $where_conditions);

    // Get payments
    $stmt = "SELECT payment.payment_id, payment.date, payment.amount, payment.description,
             orders.ser as invoice_number, orders.order_date, orders.pr_af_dis as invoice_total, orders.balance as invoice_balance,
             customer.cust_name, customer.cust_phone,
             account.account_name, account.account_number,
             users.fullname as created_by
             FROM payment
             INNER JOIN orders ON payment.order_id = orders.order_id
             INNER JOIN customer ON orders.cust_id = customer.customer_id
             INNER JOIN account ON payment.account = account.account_id
             LEFT JOIN users ON payment.created_by = users.userid
             WHERE $where_clause
             ORDER BY payment.date DESC, payment.payment_id DESC";
    
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "<div class='alert alert-danger'>Error loading report: " . mysqli_error($con) . "</div>";
        exit;
    }

    $total_payments = 0;
    $payment_count = 0;

    echo "
    <div class='mb-3'>
        <h4>Payment Report</h4>
        <p><strong>Period:</strong> " . date('M d, Y', strtotime($fdate)) . " to " . date('M d, Y', strtotime($tdate)) . "</p>
    </div>

    <div class='table-responsive'>
        <table class='table table-bordered table-striped table-hover'>
            <thead class='bg-primary text-white'>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Invoice#</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Invoice Total</th>
                    <th>Payment Amount</th>
                    <th>Invoice Balance</th>
                    <th>Payment Account</th>
                    <th>Received By</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
    ";

    if(mysqli_num_rows($result) > 0){
        $i = 1;
        while($row = mysqli_fetch_assoc($result)){
            $payment_date = date('M d, Y h:i A', strtotime($row['date']));
            $invoice_number = $row['invoice_number'];
            $customer_name = $row['cust_name'];
            $customer_phone = $row['cust_phone'];
            $invoice_total = number_format($row['invoice_total'], 2);
            $payment_amount = number_format($row['amount'], 2);
            $invoice_balance = number_format($row['invoice_balance'], 2);
            $account_name = $row['account_name'] . ' - ' . $row['account_number'];
            $created_by = $row['created_by'] ?? 'N/A';
            $note = !empty($row['description']) ? $row['description'] : '-';

            $total_payments += $row['amount'];
            $payment_count++;

            echo "
            <tr>
                <td>$i</td>
                <td>$payment_date</td>
                <td><strong>$invoice_number</strong></td>
                <td>$customer_name</td>
                <td>$customer_phone</td>
                <td>\$$invoice_total</td>
                <td><strong class='text-success'>\$$payment_amount</strong></td>
                <td>\$$invoice_balance</td>
                <td>$account_name</td>
                <td>$created_by</td>
                <td>$note</td>
            </tr>
            ";
            $i++;
        }
    } else {
        echo "
        <tr>
            <td colspan='11' class='text-center text-muted'>No payment records found for the selected period</td>
        </tr>
        ";
    }

    echo "
            </tbody>
            <tfoot class='bg-light'>
                <tr>
                    <th colspan='6' class='text-right'>Total Payments ($payment_count records):</th>
                    <th colspan='5' class='text-success'>\$" . number_format($total_payments, 2) . "</th>
                </tr>
            </tfoot>
        </table>
    </div>
    ";

    // Summary cards
    if($payment_count > 0){
        $avg_payment = $total_payments / $payment_count;
        
        echo "
        <div class='row mt-3'>
            <div class='col-md-3'>
                <div class='card bg-success text-white'>
                    <div class='card-body text-center'>
                        <h5>Total Payments</h5>
                        <h3>\$" . number_format($total_payments, 2) . "</h3>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card bg-info text-white'>
                    <div class='card-body text-center'>
                        <h5>Payment Count</h5>
                        <h3>$payment_count</h3>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card bg-primary text-white'>
                    <div class='card-body text-center'>
                        <h5>Average Payment</h5>
                        <h3>\$" . number_format($avg_payment, 2) . "</h3>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='card bg-warning text-white'>
                    <div class='card-body text-center'>
                        <h5>Period</h5>
                        <h3>" . ceil((strtotime($tdate) - strtotime($fdate)) / 86400) . " Days</h3>
                    </div>
                </div>
            </div>
        </div>
        ";
    }
}
?>


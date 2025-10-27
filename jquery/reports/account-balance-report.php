<?php
session_start();
include('../../path.php');
include(ROOT_PATH . '/inc/config.php');

if(isset($_POST['show_report'])){
    $fdate = mysqli_real_escape_string($con, $_POST['fdate']);
    $tdate = mysqli_real_escape_string($con, $_POST['tdate']);
    $account_filter = isset($_POST['account']) ? intval($_POST['account']) : 0;

    // Build account filter
    $account_where = "";
    if($account_filter > 0){
        $account_where = " AND account_id = $account_filter";
    }

    // Get all accounts
    $stmt = "SELECT * FROM account WHERE 1=1 $account_where ORDER BY account_name ASC";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "<div class='alert alert-danger'>Error loading report: " . mysqli_error($con) . "</div>";
        exit;
    }

    $grand_total_income = 0;
    $grand_total_expenses = 0;
    $grand_total_purchases = 0;
    $grand_total_balance = 0;

    echo "
    <div class='mb-3'>
        <h4>Account Balance Report</h4>
        <p><strong>Period:</strong> " . date('M d, Y', strtotime($fdate)) . " to " . date('M d, Y', strtotime($tdate)) . "</p>
    </div>

    <div class='table-responsive'>
        <table class='table table-bordered table-striped table-hover'>
            <thead class='bg-primary text-white'>
                <tr>
                    <th>#</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Sales Income</th>
                    <th>Expenses</th>
                    <th>Purchase Expenses</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
    ";

    if(mysqli_num_rows($result) > 0){
        $i = 1;
        while($row = mysqli_fetch_assoc($result)){
            $account_id = $row['account_id'];
            $account_name = $row['account_name'];
            $account_number = $row['account_number'];

            // Get total sales income (from payment table - sales payments)
            $sales_stmt = "SELECT SUM(payment.amount) as total_income 
                          FROM payment 
                          INNER JOIN orders ON payment.order_id = orders.order_id
                          WHERE payment.account = $account_id 
                          AND DATE(payment.date) BETWEEN '$fdate' AND '$tdate'";
            $sales_result = mysqli_query($con, $sales_stmt);
            $sales_row = mysqli_fetch_assoc($sales_result);
            $total_income = $sales_row['total_income'] ?? 0;

            // Get total expenses (from expense table)
            $expense_stmt = "SELECT SUM(amount) as total_expense 
                            FROM expense 
                            WHERE account = $account_id 
                            AND DATE(reg_date) BETWEEN '$fdate' AND '$tdate'";
            $expense_result = mysqli_query($con, $expense_stmt);
            $expense_row = mysqli_fetch_assoc($expense_result);
            $total_expense = $expense_row['total_expense'] ?? 0;

            // Get total purchase expenses (from pur_payments table)
            $purchase_stmt = "SELECT SUM(amount) as total_purchase 
                             FROM pur_payments 
                             WHERE account = $account_id 
                             AND DATE(date) BETWEEN '$fdate' AND '$tdate'";
            $purchase_result = mysqli_query($con, $purchase_stmt);
            $purchase_row = mysqli_fetch_assoc($purchase_result);
            $total_purchase = $purchase_row['total_purchase'] ?? 0;

            // Calculate balance: Sales Income - Expenses - Purchase Expenses
            $balance = $total_income - $total_expense - $total_purchase;

            // Add to grand totals
            $grand_total_income += $total_income;
            $grand_total_expenses += $total_expense;
            $grand_total_purchases += $total_purchase;
            $grand_total_balance += $balance;

            // Determine balance color
            $balance_class = $balance >= 0 ? 'text-success' : 'text-danger';

            echo "
            <tr>
                <td>$i</td>
                <td><strong>$account_name</strong></td>
                <td>$account_number</td>
                <td class='text-success'>\$" . number_format($total_income, 2) . "</td>
                <td class='text-danger'>\$" . number_format($total_expense, 2) . "</td>
                <td class='text-warning'>\$" . number_format($total_purchase, 2) . "</td>
                <td class='$balance_class'><strong>\$" . number_format($balance, 2) . "</strong></td>
            </tr>
            ";
            $i++;
        }
    } else {
        echo "
        <tr>
            <td colspan='7' class='text-center text-muted'>No accounts found</td>
        </tr>
        ";
    }

    // Determine grand balance color
    $grand_balance_class = $grand_total_balance >= 0 ? 'text-success' : 'text-danger';

    echo "
            </tbody>
            <tfoot class='bg-light'>
                <tr>
                    <th colspan='3' class='text-right'>GRAND TOTAL:</th>
                    <th class='text-success'>\$" . number_format($grand_total_income, 2) . "</th>
                    <th class='text-danger'>\$" . number_format($grand_total_expenses, 2) . "</th>
                    <th class='text-warning'>\$" . number_format($grand_total_purchases, 2) . "</th>
                    <th class='$grand_balance_class'><strong>\$" . number_format($grand_total_balance, 2) . "</strong></th>
                </tr>
            </tfoot>
        </table>
    </div>
    ";

    // Summary cards
    echo "
    <div class='row mt-3'>
        <div class='col-md-3'>
            <div class='card bg-success text-white'>
                <div class='card-body text-center'>
                    <h5>Total Sales Income</h5>
                    <h3>\$" . number_format($grand_total_income, 2) . "</h3>
                    <small>From customer payments</small>
                </div>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='card bg-danger text-white'>
                <div class='card-body text-center'>
                    <h5>Total Expenses</h5>
                    <h3>\$" . number_format($grand_total_expenses, 2) . "</h3>
                    <small>Business expenses</small>
                </div>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='card bg-warning text-white'>
                <div class='card-body text-center'>
                    <h5>Purchase Expenses</h5>
                    <h3>\$" . number_format($grand_total_purchases, 2) . "</h3>
                    <small>Supplier payments</small>
                </div>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='card " . ($grand_total_balance >= 0 ? 'bg-primary' : 'bg-dark') . " text-white'>
                <div class='card-body text-center'>
                    <h5>Net Balance</h5>
                    <h3>\$" . number_format($grand_total_balance, 2) . "</h3>
                    <small>Income - Expenses - Purchases</small>
                </div>
            </div>
        </div>
    </div>
    ";

    // Breakdown chart data (for future enhancement)
    if($grand_total_income > 0){
        $expense_percent = ($grand_total_expenses / $grand_total_income) * 100;
        $purchase_percent = ($grand_total_purchases / $grand_total_income) * 100;
        $balance_percent = ($grand_total_balance / $grand_total_income) * 100;

        echo "
        <div class='row mt-3'>
            <div class='col-md-12'>
                <div class='card'>
                    <div class='card-header bg-info text-white'>
                        <h5>Financial Breakdown</h5>
                    </div>
                    <div class='card-body'>
                        <div class='mb-2'>
                            <strong>Expenses:</strong> " . number_format($expense_percent, 2) . "% of income
                            <div class='progress' style='height: 25px;'>
                                <div class='progress-bar bg-danger' role='progressbar' style='width: " . number_format($expense_percent, 2) . "%;' aria-valuenow='" . number_format($expense_percent, 2) . "' aria-valuemin='0' aria-valuemax='100'>" . number_format($expense_percent, 1) . "%</div>
                            </div>
                        </div>
                        <div class='mb-2'>
                            <strong>Purchase Expenses:</strong> " . number_format($purchase_percent, 2) . "% of income
                            <div class='progress' style='height: 25px;'>
                                <div class='progress-bar bg-warning' role='progressbar' style='width: " . number_format($purchase_percent, 2) . "%;' aria-valuenow='" . number_format($purchase_percent, 2) . "' aria-valuemin='0' aria-valuemax='100'>" . number_format($purchase_percent, 1) . "%</div>
                            </div>
                        </div>
                        <div class='mb-2'>
                            <strong>Net Balance:</strong> " . number_format($balance_percent, 2) . "% of income
                            <div class='progress' style='height: 25px;'>
                                <div class='progress-bar " . ($balance_percent >= 0 ? 'bg-success' : 'bg-dark') . "' role='progressbar' style='width: " . abs(number_format($balance_percent, 2)) . "%;' aria-valuenow='" . number_format($balance_percent, 2) . "' aria-valuemin='0' aria-valuemax='100'>" . number_format($balance_percent, 1) . "%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
    }
}
?>


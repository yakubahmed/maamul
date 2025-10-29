<?php
session_start();
include('../../path.php');
include(ROOT_PATH . '/inc/config.php');

date_default_timezone_set('Africa/Nairobi');

// Ensure tables exist (idempotent)
mysqli_query($con, "CREATE TABLE IF NOT EXISTS money_loan (
  loan_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  loan_date DATETIME DEFAULT NULL,
  principal DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  paid_amount DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  balance DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  account INT(11) DEFAULT NULL,
  note VARCHAR(255) DEFAULT NULL,
  status VARCHAR(50) DEFAULT 'Active',
  created_by INT(11) DEFAULT NULL,
  created_date DATETIME DEFAULT NULL,
  warehouse INT(11) DEFAULT 1,
  PRIMARY KEY (loan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

mysqli_query($con, "CREATE TABLE IF NOT EXISTS money_loan_payment (
  mlp_id INT(11) NOT NULL AUTO_INCREMENT,
  loan_id INT(11) NOT NULL,
  amount DECIMAL(11,2) NOT NULL DEFAULT 0.00,
  date DATETIME DEFAULT NULL,
  account INT(11) DEFAULT NULL,
  note VARCHAR(255) DEFAULT NULL,
  created_by INT(11) DEFAULT NULL,
  PRIMARY KEY (mlp_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

$action = isset($_POST['action']) ? $_POST['action'] : '';

if($action === 'create'){
  $customer = intval($_POST['customer'] ?? 0);
  $loan_date = mysqli_real_escape_string($con, $_POST['loan_date'] ?? date('Y-m-d'));
  $amount = floatval($_POST['amount'] ?? 0);
  $account = !empty($_POST['account']) ? intval($_POST['account']) : 'NULL';
  $note = mysqli_real_escape_string($con, $_POST['note'] ?? '');
  $uid = intval($_SESSION['uid'] ?? 0);
  $now = date('Y-m-d H:i:s');

  if($customer <= 0 || $amount <= 0){ echo 'invalid'; exit; }

  $stmt = "INSERT INTO money_loan (customer_id, loan_date, principal, paid_amount, balance, account, note, status, created_by, created_date, warehouse)
           VALUES ($customer, '$loan_date', $amount, 0.00, $amount, $account, '$note', 'Active', $uid, '$now', 1)";
  if(mysqli_query($con, $stmt)){
    echo 'success';
  } else {
    echo 'db_error: ' . mysqli_error($con);
  }
  exit;
}

if($action === 'list'){
  $rows = [];
  $sql = "SELECT ml.*, c.cust_name, c.cust_phone
          FROM money_loan ml
          INNER JOIN customer c ON c.customer_id = ml.customer_id
          ORDER BY ml.loan_id DESC";
  $res = mysqli_query($con, $sql);

  ob_start();
  echo "<div class='table-responsive'>
        <table class='table table-bordered table-striped'>
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Loan Date</th>
              <th>Principal</th>
              <th>Paid</th>
              <th>Balance</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>";
  if($res && mysqli_num_rows($res) > 0){
    $i=0;
    while($r = mysqli_fetch_assoc($res)){
      $i++;
      $balanceClass = ((float)$r['balance'] <= 0.0001) ? 'badge badge-success' : 'badge badge-warning';
      $status = ((float)$r['balance'] <= 0.0001) ? 'Closed' : $r['status'];
      echo "<tr>
        <td>$i</td>
        <td>".htmlspecialchars($r['cust_name'])."</td>
        <td>".htmlspecialchars($r['cust_phone'])."</td>
        <td>".date('M d, Y', strtotime($r['loan_date']))."</td>
        <td>$".number_format($r['principal'],2)."</td>
        <td class='text-success'>$".number_format($r['paid_amount'],2)."</td>
        <td><span class='".$balanceClass."'>$".number_format($r['balance'],2)."</span></td>
        <td>$status</td>
        <td>
          <button class='btn btn-sm btn-success btn-add-payment' data-id='".$r['loan_id']."' ".(((float)$r['balance']<=0)?'disabled':'').">
            <i class='fa fa-plus'></i> Payment
          </button>
        </td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='9' class='text-center text-muted'>No money loans found</td></tr>";
  }
  echo "</tbody></table></div>";
  echo ob_get_clean();
  exit;
}

if($action === 'add_payment'){
  $loan_id = intval($_POST['loan_id'] ?? 0);
  $amount = floatval($_POST['amount'] ?? 0);
  $date = mysqli_real_escape_string($con, $_POST['date'] ?? date('Y-m-d'));
  $account = !empty($_POST['account']) ? intval($_POST['account']) : 'NULL';
  $note = mysqli_real_escape_string($con, $_POST['note'] ?? '');
  $uid = intval($_SESSION['uid'] ?? 0);

  if($loan_id <= 0 || $amount <= 0){ echo 'invalid'; exit; }

  // Insert payment
  $ins = "INSERT INTO money_loan_payment (loan_id, amount, date, account, note, created_by)
          VALUES ($loan_id, $amount, '$date', $account, '$note', $uid)";
  if(!mysqli_query($con, $ins)){
    echo 'db_error: ' . mysqli_error($con); exit;
  }

  // Update aggregates
  $sumq = mysqli_query($con, "SELECT COALESCE(SUM(amount),0) AS paid FROM money_loan_payment WHERE loan_id = $loan_id");
  $paid = 0.0; if($sumq){ $row = mysqli_fetch_assoc($sumq); $paid = (float)$row['paid']; }

  $upd = "UPDATE money_loan SET paid_amount = $paid, balance = GREATEST(principal - $paid, 0), status = (CASE WHEN (principal - $paid) <= 0 THEN 'Closed' ELSE 'Active' END) WHERE loan_id = $loan_id";
  mysqli_query($con, $upd);

  echo 'success';
  exit;
}

echo 'no_action';


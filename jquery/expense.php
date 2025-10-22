<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['etype'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $etype = mysqli_real_escape_string($con, $_POST['etype']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);
    $acc = mysqli_real_escape_string($con, $_POST['account']);
    $userid = $_SESSION['uid'];


    // Validate input
    if(empty(trim($name)) || empty(trim($amount)) || !is_numeric($amount)){
        echo json_encode(array('error' => 'invalid_input'));
        exit;
    }

    $stmt = "INSERT INTO expense(expense_type, expense_name, amount, description, warehouse, reg_date, reg_by,account )
    VALUES ($etype, '$name', $amount, '$desc', 1, '$date', $userid, $acc)";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
    }

}

if(isset($_POST['del_exp'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_exp'];

    $stmt = "DELETE FROM expense WHERE expense_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}

if(isset($_POST['editSingleExp'])){
    $id = $_POST['editSingleExp'];
    $stmt = "SELECT * FROM expense WHERE expense_id = $id";
    $result = mysqli_query($con,$stmt);
    while($row = mysqli_fetch_assoc($result)){
        $name = $row['expense_name'];
        $type = $row['expense_type'];
        $desc = $row['description'];
        $amount = $row['amount'];
        $acc = $row['account'];
        $date  = date('Y-m-d', strtotime($row['reg_date']));

        echo "
        <div class='form-group col-md-4'>
        <label for=''> Expense type *</label>
        <select name='etype1' id='etype1' class='form-control' required>";

        $sql = "SELECT * FROM expense_type WHERE expense_type_id = $id";
        $re = mysqli_query($con, $sql);
        while($ro = mysqli_fetch_assoc($re)){
            $id  = $ro['expense_type_id'];
            $name  = $ro['name'];
            echo "
                <option value='$id'>$name</option>
            ";
        }

        $sql = "SELECT * FROM expense_type WHERE expense_type_id != $id";
        $re = mysqli_query($con, $sql);
        while($ro = mysqli_fetch_assoc($re)){
            $id  = $ro['expense_type_id'];
            $name  = $ro['name'];
            echo "
                <option value='$id'>$name</option>
            ";
        }

     echo "   </select>
      </div>

      <div class='form-group col-md-4'>
        <label for=''> Expense For *</label>
        <input type='hidden' name='exp' id='exp' value='$id'>
        <input type='text' name='name1' value='$name' id='name1' class='form-control' required placeholder='Enter expense for' autocomplete='off'>
      </div>
      <div class='form-group col-md-4'>
        <label for=''> Date *</label>
        <input type='date' name='date1'  id='date1' value='$date' class='form-control'>
      </div>

      <div class='form-group col-md-6'>
        <label for=''> Amount</label>
        <div class='input-group rounded-0'>
          <label class='input-group-prepend' for='pi9'><span class='badge'>$</span></label> <input type='number' name='amount1' id='amount1'  autocomplete='off' placeholder='Enter amount' step='0.01' value='$amount' required class='form-control rounded-0' id='pi9'>
        </div>
      </div>

      <div class='form-group col-md-6'>
        <label for=''> Account *</label>
        <select name='account1' id='account1' class='form-control' required>";
        $stmt = "SELECT * FROM account WHERE account_id = $acc";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id  = $row['account_id'];
            $name  = $row['account_name'];
            $num  = $row['account_number'];
            echo "
                <option value='$id'>$name - $num</option>
            ";
        }
          
        $stmt = "SELECT * FROM account WHERE account_id != $acc";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id  = $row['account_id'];
            $name  = $row['account_name'];
            $num  = $row['account_number'];
            echo "
                <option value='$id'>$name - $num</option>
            ";
        }
          
    echo"    </select>
      </div>

      <div class='form-group col-md-12'>
        <label for=''> Description</label>
        <textarea name='desc1' id='desc1'  class='form-control ' required>$desc</textarea>
      </div>

      <div class='form-group col-md-6'>
        <button type='submit' class='btn btn-info rounded-0'>Update expense</button>
      </div>
        ";
    }
}

if(isset($_POST['exp'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = mysqli_real_escape_string($con, $_POST['exp']);
    $etype = mysqli_real_escape_string($con, $_POST['etype1']);
    $date = mysqli_real_escape_string($con, $_POST['date1']);
    $amount = mysqli_real_escape_string($con, $_POST['amount1']);
    $name = mysqli_real_escape_string($con, $_POST['name1']);
    $desc = mysqli_real_escape_string($con, $_POST['desc1']);
    $acc = mysqli_real_escape_string($con, $_POST['account1']);
    $userid = $_SESSION['uid'];

    // Validate input
    if(empty(trim($name)) || empty(trim($amount)) || !is_numeric($amount)){
        echo json_encode(array('error' => 'invalid_input'));
        exit;
    }

    $stmt = "UPDATE expense SET expense_name = '$name', expense_type = $etype, amount = $amount, description= '$desc', 
    account  = $acc WHERE expense_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
    }


}
?>
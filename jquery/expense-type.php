<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

// Check for session timeout before including config
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired - return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Session expired', 'redirect' => true));
    exit();
}


include('../inc/config.php');

// Note: Set Content-Type per branch to match response format

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['etname'])){
    header('Content-Type: application/json');
    $name = mysqli_real_escape_string($con, $_POST['etname']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);

    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    // Validate input
    if(empty(trim($name))){
        echo json_encode(array('error' => 'empty_name'));
        exit;
    }

    ///Checking if expense type is registered
    $stmt = "SELECT * FROM expense_type WHERE name = '$name' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        echo json_encode(array('error' => 'found_etype'));
    }else{
        $stmt = "INSERT INTO expense_type (name, description,warehouse, reg_date, reg_by) 
        VALUES ('$name', '$desc', 1, '$date', $userid)";
        $result = mysqli_query($con, $stmt); 
        if($result){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
        }
    }

}

if(isset($_POST['all_unit'])){
    header('Content-Type: text/html; charset=UTF-8');
    $i = 0;
    $stmt = "SELECT * FROM unit ORDER BY unit_id DESC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
      $i++;
      $id = $row['unit_id'];
      $name = $row['unit_name'];
      $des = $row['shortname'];
      $date = date("M d, Y", strtotime($row['reg_date']));
      if($des == "" ){ $des = "N/A"; }

      echo "
        <tr>
          <td>$i</td>
          <td>$name</td>
          <td>$des</td>
          <td>$date</td>
          <td>
            <div class='btn-group btn-group-toggle' data-toggle='buttons'>
              <button type='button' class='btn btn-warning btn-sm'  data-toggle='tooltip' data-placement='top' title='Edit Category' id='edit_unit' data-id='$id'> <i class='fa fa-edit'></i> </button>
              <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete category' id='del_unit' data-id='$id'> <i class='fa fa-trash'></i></button>
            </div>
          </td>
        </tr>
      ";

    }
}


if(isset($_POST['del_etype'])){
    header('Content-Type: application/json');
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_etype'];

    $stmt = "DELETE FROM expense_type WHERE expense_type_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}

if(isset($_POST['edit_etype'])){
    header('Content-Type: text/html; charset=UTF-8');
    $id = $_POST['edit_etype'];

    $stmt = "SELECT * FROM expense_type WHERE expense_type_id = $id";
    $result = mysqli_query($con, $stmt);
    if($row = mysqli_fetch_assoc($result)){
        $id = $row['expense_type_id'];
        $name = $row['name'];
        $sname = $row['description'];

        echo "
        <div class='form-group col-md-12'>
            <label for=''> Expense type name *</label>
            <input type='hidden' name='upd_role' id='upd_role' value='$id'>
            <input type='text' name='rname1' id='rname1' value='$name' minlength='3' class='form-control rounded-0' autocomplete='off' required>
        </div>

      <div class='form-group col-md-12'>
        <label for=''> Description </label>
        <textarea name='desc1' id='desc1' class='form-control'>$sname</textarea>
      </div>

      <div class='form-group col-md-12'>
        <button type='submit' class='btn btn-success'> <i class='fa fa-edit'></i> Update expense type</button>
      </div>
        ";
    }
}


if(isset($_POST['upd_role'])){
    header('Content-Type: application/json');
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['upd_role'];
    $uname = mysqli_real_escape_string($con, $_POST['rname1']);
    $sname = mysqli_real_escape_string($con, $_POST['desc1']);


    $sql = "SELECT * FROM expense_type WHERE expense_type_id = $id";
    $re = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($re);
    $curr_role = $ro['name'];

    if($curr_role != $uname){
        $stmt = "SELECT * FROM expense_type WHERE name = '$uname'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_etype'));
        }else{
        
          
            $stmt = "UPDATE expense_type SET name = '$uname', description = '$sname' WHERE expense_type_id = $id";
            $result = mysqli_query($con, $stmt);

            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
            }
            
        }
    }else{
            $stmt = "UPDATE expense_type SET name = '$uname', description = '$sname' WHERE expense_type_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
        }
    }

  
}


?>

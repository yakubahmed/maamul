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

date_default_timezone_set('Africa/Nairobi');

// Determine if we should send JSON or HTML based on the request
$send_json = !isset($_POST['edit_unit']) && !isset($_POST['all_unit']);

// Set proper headers for AJAX requests (except for edit_unit and all_unit which return HTML)
if($send_json){
    header('Content-Type: application/json');
}

if(isset($_POST['uname'])){
    $cname = mysqli_real_escape_string($con, $_POST['uname']);
    $sname = mysqli_real_escape_string($con, $_POST['sname']);

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

    ///Checking if unit is registered
    $stmt = "SELECT * FROM unit WHERE unit_name = '$cname' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        echo json_encode(array('error' => 'found_unit'));
    }else{
        $stmt = "SELECT * FROM unit WHERE shortname = '$sname'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_sname'));
        }else{
            $stmt = "INSERT INTO unit (unit_name, shortname, reg_date, reg_by, warehouse) 
            VALUES ('$cname', '$sname', '$date', '$userid', 1)";
            $result = mysqli_query($con, $stmt); 
            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
            }
        }
    }

}

if(isset($_POST['all_unit'])){
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


if(isset($_POST['del_unit'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_unit'];

    $stmt = "DELETE FROM unit WHERE unit_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}

if(isset($_POST['edit_unit'])){
    // Validate ID
    if(empty($_POST['edit_unit'])){
        echo '<div class="alert alert-danger">Invalid unit ID</div>';
        exit;
    }
    
    $id = intval($_POST['edit_unit']);
    
    if($id <= 0){
        echo '<div class="alert alert-danger">Invalid unit ID</div>';
        exit;
    }

    $stmt = "SELECT * FROM unit WHERE unit_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo '<div class="alert alert-danger">Database error: ' . mysqli_error($con) . '</div>';
        exit;
    }
    
    if(mysqli_num_rows($result) == 0){
        echo '<div class="alert alert-danger">Unit not found</div>';
        exit;
    }
    
    $row = mysqli_fetch_assoc($result);
    $id = $row['unit_id'];
    $name = htmlspecialchars($row['unit_name'], ENT_QUOTES);
    $sname = htmlspecialchars($row['shortname'], ENT_QUOTES);

    echo "
    <div class='form-group col-md-12'>
        <label for=''> Unit name *</label>
        <input type='hidden' name='unit_id' id='unit_id' value='$id'>
        <input type='text' name='uname1' id='uname1' minlength='5' maxlength='30' value='$name' class='form-control rounded-0' autocomplete='off' required>
    </div>

    <div class='form-group col-md-12'>
        <label for=''> Short name </label>
        <input type='text' name='sname1' id='sname1' maxlength='3' value='$sname' class='form-control rounded-0'>
    </div>

    <div class='form-group col-md-6'>
        <button type='submit' class='btn btn-success'> <i class='fa fa-save'></i> Update Unit</button>
    </div>
    <div class='form-group col-md-6'>
        <button type='button' class='btn btn-secondary' id='btnCancelEditUnit'> <i class='fa fa-times'></i> Cancel</button>
    </div>
    ";
    exit;
}


if(isset($_POST['unit_id'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    // Validate required fields
    if(empty($_POST['unit_id']) || empty($_POST['uname1'])){
        echo json_encode(array('error' => 'missing_fields'));
        exit;
    }
    
    $id = intval($_POST['unit_id']);
    $uname = mysqli_real_escape_string($con, $_POST['uname1']);
    $sname = mysqli_real_escape_string($con, $_POST['sname1']);
    
    if($id <= 0){
        echo json_encode(array('error' => 'invalid_id'));
        exit;
    }
    
    // Validate unit name is not empty
    if(empty(trim($uname))){
        echo json_encode(array('error' => 'missing_fields'));
        exit;
    }

    // Get current unit data
    $sql = "SELECT * FROM unit WHERE unit_id = $id";
    $re = mysqli_query($con, $sql);
    
    if(!$re){
        echo json_encode(array('error' => 'database_error', 'message' => mysqli_error($con)));
        exit;
    }
    
    if(mysqli_num_rows($re) == 0){
        echo json_encode(array('error' => 'unit_not_found'));
        exit;
    }
    
    $ro = mysqli_fetch_assoc($re);
    $curr_uname = $ro['unit_name'];
    $curr_sname = $ro['shortname'];

    // Check for duplicate unit name if name is being changed
    if($curr_uname != $uname){
        $stmt = "SELECT * FROM unit WHERE unit_name = '$uname' AND unit_id != $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo json_encode(array('error' => 'database_error', 'message' => mysqli_error($con)));
            exit;
        }
        
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_uname'));
            exit;
        }
    }
    
    // Check for duplicate short name if shortname is being changed and not empty
    if(!empty($sname) && $curr_sname != $sname){
        $stmt = "SELECT * FROM unit WHERE shortname = '$sname' AND unit_id != $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo json_encode(array('error' => 'database_error', 'message' => mysqli_error($con)));
            exit;
        }
        
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_sname'));
            exit;
        }
    }
    
    // Update unit
    $stmt = "UPDATE unit SET unit_name = '$uname', shortname = '$sname' WHERE unit_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo json_encode(array('error' => 'update_error', 'message' => mysqli_error($con)));
        exit;
    }
    
    if($result){
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'failed'));
    }
    exit;
}


?>
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
$send_json = !isset($_POST['edit_cat']) && !isset($_POST['all_categores']);

// Set proper headers for AJAX requests (except for edit_cat and all_categores which return HTML)
if($send_json){
    header('Content-Type: application/json');
}

if(isset($_POST['cname'])){
    $cname = mysqli_real_escape_string($con, $_POST['cname']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);

    // Check if user is logged in
    if(!isset($_SESSION['uid'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    // Check if category name is not empty
    if(empty(trim($cname))){
        echo json_encode(array('error' => 'empty_name'));
        exit;
    }

    // Debug: Log the values being processed
    error_log("Category registration attempt - Name: $cname, Desc: $desc, UserID: $userid, Date: $date");

    // First, test if the table exists and is accessible
    $test_query = "SHOW TABLES LIKE 'item_category'";
    $test_result = mysqli_query($con, $test_query);
    if(!$test_result || mysqli_num_rows($test_result) == 0){
        echo json_encode(array('error' => 'table_not_found'));
        exit;
    }

    ///Checking if category is registered
    $stmt = "SELECT * FROM item_category WHERE category_name = '$cname'";
    $result = mysqli_query($con, $stmt); 
    
    if(!$result){
        echo json_encode(array('error' => 'database_error', 'message' => mysqli_error($con)));
        exit;
    }
    
    if(mysqli_num_rows($result) > 0){
        echo json_encode(array('error' => 'found_cat'));
    }else{
        $stmt = "INSERT INTO item_category (category_name, description, reg_date, reg_by, warehouse) 
        VALUES ('$cname', '$desc', '$date', $userid, 1)";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
        }
    }
}

if(isset($_POST['all_categores'])){
    $i = 0;
    $stmt = "SELECT * FROM item_category ORDER BY itemcat_id DESC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
      $i++;
      $id = $row['itemcat_id'];
      $name = $row['category_name'];
      $des = $row['description'];
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
            <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editCustModal' data-toggle='tooltip' data-placement='top' title='Edit Category' id='edit_cat' data-id='$id'> <i class='fa fa-edit'></i> </button>
            <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete category' id='del_cat' data-id='$id'> <i class='fa fa-trash'></i></button>
          </div>
        </td>
      </tr>
    ";

    }
}

if(isset($_POST['del_cat'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_cat'];
    
    $stmt = "DELETE FROM item_category WHERE itemcat_id = $id";
    $result = mysqli_query($con, $stmt); 
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}

if(isset($_POST['edit_cat'])){
    // Validate ID
    if(empty($_POST['edit_cat'])){
        echo '<div class="alert alert-danger">Invalid category ID</div>';
        exit;
    }
    
    $id = intval($_POST['edit_cat']);
    
    if($id <= 0){
        echo '<div class="alert alert-danger">Invalid category ID</div>';
        exit;
    }

    $stmt = "SELECT * FROM item_category WHERE itemcat_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo '<div class="alert alert-danger">Database error: ' . mysqli_error($con) . '</div>';
        exit;
    }
    
    if(mysqli_num_rows($result) == 0){
        echo '<div class="alert alert-danger">Category not found</div>';
        exit;
    }
    
    $row = mysqli_fetch_assoc($result);
    $id = $row['itemcat_id'];
    $name = htmlspecialchars($row['category_name'], ENT_QUOTES);
    $des = htmlspecialchars($row['description'], ENT_QUOTES);
    
    echo "
        <div class='form-group col-md-12'>
            <label for=''> Category  name *</label>
            <input type='hidden' name='cat_id' id='cat_id' value='$id'>
            <input type='text' name='cname1' id='cname1' value='$name' class='form-control rounded-0' autocomplete='off' required>
        </div>

        <div class='form-group col-md-12'>
            <label for=''> Description </label>
            <textarea name='desc1' id='desc1' class='form-control rounded-0'>$des</textarea>
        </div>

        <div class='form-group col-md-6'>
            <button type='submit' class='btn btn-success'> <i class='fa fa-save'></i> Update category</button>
        </div>
        <div class='form-group col-md-6'>
            <button type='button' class='btn btn-secondary' id='btnCancelEdit'> <i class='fa fa-times'></i> Cancel</button>
        </div>
    ";
    exit;
}


if(isset($_POST['cat_id'])){
    // Validate required fields
    if(empty($_POST['cat_id']) || empty($_POST['cname1'])){
        echo "missing_fields";
        exit;
    }
    
    $id = intval($_POST['cat_id']);
    $cname = mysqli_real_escape_string($con, $_POST['cname1']);
    $desc = mysqli_real_escape_string($con, $_POST['desc1']);
    
    if($id <= 0){
        echo "invalid_id";
        exit;
    }
    
    // Validate category name is not empty
    if(empty(trim($cname))){
        echo "missing_fields";
        exit;
    }

    // Get current category data
    $sql = "SELECT * FROM item_category WHERE itemcat_id = $id";
    $res = mysqli_query($con, $sql);
    
    if(!$res){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($res) == 0){
        echo "category_not_found";
        exit;
    }
    
    $ro = mysqli_fetch_assoc($res);
    $curr_cname = $ro['category_name'];

    // Check for duplicate category name if name is being changed
    if($curr_cname != $cname){
        $s = "SELECT * FROM item_category WHERE category_name = '$cname' AND itemcat_id != $id";
        $re = mysqli_query($con, $s);
        
        if(!$re){
            echo "database_error: " . mysqli_error($con);
            exit;
        }
        
        if(mysqli_num_rows($re) > 0){
            echo "found_cat";
            exit;
        }
    }
    
    // Update category
    $stmt = "UPDATE item_category SET category_name = '$cname', description = '$desc' WHERE itemcat_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if($result){
        echo "updated";
    } else {
        echo "failed";
    }
    exit;
}

?>
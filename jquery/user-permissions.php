<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['action'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $action = $_POST['action'];
    
    switch($action){
        case 'add_permission':
            $group_id = $_POST['group_id'];
            $sub_menu_id = $_POST['sub_menu_id'];
            $view = $_POST['view'];
            $add = $_POST['add'];
            $edit = $_POST['edit'];
            $delete = $_POST['delete'];
            $date = date('Y-m-d H:i:s');
            
            // Validate input
            if(empty($group_id) || empty($sub_menu_id)){
                echo json_encode(array('error' => 'empty_fields'));
                break;
            }
            
            // Check if permission already exists
            $stmt = "SELECT * FROM previlage WHERE group_id = $group_id AND sub_menu_id = $sub_menu_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                echo json_encode(array('error' => 'exists'));
            } else {
                $stmt = "INSERT INTO previlage (group_id, sub_menu_id, date, view, add, edit, delete) 
                         VALUES ($group_id, $sub_menu_id, '$date', $view, $add, $edit, $delete)";
                if(mysqli_query($con, $stmt)){
                    echo json_encode(array('success' => true));
                } else {
                    echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
                }
            }
            break;
            
        case 'get_permission':
            $prev_id = $_POST['prev_id'];
            $stmt = "SELECT * FROM previlage WHERE prev_id = $prev_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                echo json_encode($row);
            }
            break;
            
        case 'update_permission':
            $prev_id = $_POST['prev_id'];
            $group_id = $_POST['group_id'];
            $sub_menu_id = $_POST['sub_menu_id'];
            $view = $_POST['view'];
            $add = $_POST['add'];
            $edit = $_POST['edit'];
            $delete = $_POST['delete'];
            
            // Check if permission already exists (excluding current permission)
            $stmt = "SELECT * FROM previlage WHERE group_id = $group_id AND sub_menu_id = $sub_menu_id AND prev_id != $prev_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                echo 'exists';
            } else {
                $stmt = "UPDATE previlage SET group_id = $group_id, sub_menu_id = $sub_menu_id, 
                         view = $view, add = $add, edit = $edit, delete = $delete 
                         WHERE prev_id = $prev_id";
                if(mysqli_query($con, $stmt)){
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
            break;
            
        case 'delete_permission':
            $prev_id = $_POST['prev_id'];
            $stmt = "DELETE FROM previlage WHERE prev_id = $prev_id";
            if(mysqli_query($con, $stmt)){
                echo 'success';
            } else {
                echo 'error';
            }
            break;
    }
}
?>

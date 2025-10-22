<?php 
include('../inc/session_config.php');
session_start();

include('../inc/config.php');
include('../inc/access_control.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['action'])){
    $action = $_POST['action'];
    
    // Check permissions based on action
    switch($action) {
        case 'add_group':
        case 'edit_group':
        case 'delete_group':
            secureAjaxEndpoint($con, 'edit', null); // User groups management requires edit permission
            break;
        case 'get_groups':
        case 'get_permissions':
        case 'save_permissions':
            secureAjaxEndpoint($con, 'view', null); // View permission for reading data
            break;
        default:
            secureAjaxEndpoint($con, 'view', null);
    }
    
    switch($action){
        case 'add_group':
            $group_name = mysqli_real_escape_string($con, $_POST['group_name']);
            $group_description = mysqli_real_escape_string($con, $_POST['group_description']);
            $userid = $_SESSION['uid'];
            $date = date('Y-m-d H:i:s');
            
            // Check if group name already exists
            $stmt = "SELECT * FROM usergroup WHERE group_name = '$group_name'";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                echo 'exists';
            } else {
                $stmt = "INSERT INTO usergroup (group_name, description, warehouse, created_date, created_by) 
                         VALUES ('$group_name', '$group_description', 1, '$date', $userid)";
                if(mysqli_query($con, $stmt)){
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
            break;
            
        case 'get_group':
            $group_id = $_POST['group_id'];
            $stmt = "SELECT * FROM usergroup WHERE group_id = $group_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                echo json_encode($row);
            }
            break;
            
        case 'update_group':
            $group_id = $_POST['group_id'];
            $group_name = mysqli_real_escape_string($con, $_POST['group_name']);
            $group_description = mysqli_real_escape_string($con, $_POST['group_description']);
            
            // Check if group name already exists (excluding current group)
            $stmt = "SELECT * FROM usergroup WHERE group_name = '$group_name' AND group_id != $group_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                echo 'exists';
            } else {
                $stmt = "UPDATE usergroup SET group_name = '$group_name', description = '$group_description' 
                         WHERE group_id = $group_id";
                if(mysqli_query($con, $stmt)){
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
            break;
            
        case 'delete_group':
            $group_id = $_POST['group_id'];
            
            // Check if group is being used by any users
            $stmt = "SELECT * FROM users WHERE usergroup = $group_id";
            $result = mysqli_query($con, $stmt);
            if(mysqli_num_rows($result) > 0){
                echo 'in_use';
            } else {
                $stmt = "DELETE FROM usergroup WHERE group_id = $group_id";
                if(mysqli_query($con, $stmt)){
                    // Also delete all privileges for this group
                    $stmt = "DELETE FROM previlage WHERE group_id = $group_id";
                    mysqli_query($con, $stmt);
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
            break;
            
        case 'get_permissions':
            $group_id = $_POST['group_id'];
            
            // Get all menu items from submenu table
            $stmt = "SELECT * FROM submenu ORDER BY sub_menu_name ASC";
            $result = mysqli_query($con, $stmt);
            
            // Get existing permissions for this group
            $stmt2 = "SELECT sub_menu_id FROM previlage WHERE group_id = $group_id";
            $result2 = mysqli_query($con, $stmt2);
            $existing_permissions = array();
            while($row2 = mysqli_fetch_assoc($result2)){
                $existing_permissions[] = $row2['sub_menu_id'];
            }
            
            $html = '<div class="row">';
            $html .= '<div class="col-md-12">';
            $html .= '<h6>Select permissions for this group:</h6>';
            $html .= '<div class="form-group">';
            $html .= '<button type="button" class="btn btn-sm btn-primary" id="selectAll">Select All</button>';
            $html .= '<button type="button" class="btn btn-sm btn-secondary ml-2" id="deselectAll">Deselect All</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            
            $html .= '<div class="row">';
            while($row = mysqli_fetch_assoc($result)){
                $menu_id = $row['submenu_id'];
                $menu_name = $row['sub_menu_name'];
                $checked = in_array($menu_id, $existing_permissions) ? 'checked' : '';
                
                $html .= '<div class="col-md-4 mb-2">';
                $html .= '<div class="form-check">';
                $html .= '<input class="form-check-input permission-checkbox" type="checkbox" value="' . $menu_id . '" id="perm_' . $menu_id . '" ' . $checked . '>';
                $html .= '<label class="form-check-label" for="perm_' . $menu_id . '">' . $menu_name . '</label>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
            
            echo $html;
            break;
            
        case 'save_permissions':
            $group_id = $_POST['group_id'];
            $permissions = $_POST['permissions'];
            
            // Delete existing permissions for this group
            $stmt = "DELETE FROM previlage WHERE group_id = $group_id";
            mysqli_query($con, $stmt);
            
            // Insert new permissions
            if(!empty($permissions)){
                foreach($permissions as $permission){
                    $stmt = "INSERT INTO previlage (group_id, sub_menu_id, date, view, edit, add, delete) 
                             VALUES ($group_id, $permission, NOW(), 1, 1, 1, 1)";
                    mysqli_query($con, $stmt);
                }
            }
            
            echo 'success';
            break;
    }
}
?>

<?php
// Permission checking utility functions

function hasPermission($con, $user_group_id, $sub_menu_id, $action = 'view') {
    // Quote the action column name to handle reserved keywords
    $quoted_action = "`$action`";
    $stmt = "SELECT $quoted_action FROM previlage WHERE group_id = $user_group_id AND sub_menu_id = $sub_menu_id";
    $result = mysqli_query($con, $stmt);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$action] == 1;
    }
    
    return false;
}

function checkPermission($con, $user_group_id, $sub_menu_id, $action = 'view') {
    if(!hasPermission($con, $user_group_id, $sub_menu_id, $action)) {
        header('Location: ' . BASE_URL . '403.php');
        exit();
    }
}

function canView($con, $user_group_id, $sub_menu_id) {
    return hasPermission($con, $user_group_id, $sub_menu_id, 'view');
}

function canAdd($con, $user_group_id, $sub_menu_id) {
    return hasPermission($con, $user_group_id, $sub_menu_id, 'add');
}

function canEdit($con, $user_group_id, $sub_menu_id) {
    return hasPermission($con, $user_group_id, $sub_menu_id, 'edit');
}

function canDelete($con, $user_group_id, $sub_menu_id) {
    return hasPermission($con, $user_group_id, $sub_menu_id, 'delete');
}

function getMenuItems($con, $user_group_id) {
    $stmt = "SELECT sm.*, m.menu_name, m.menu_icon, m.sort_by 
             FROM submenu sm 
             LEFT JOIN menu m ON sm.menu_id = m.menu_id 
             WHERE sm.submenu_id IN (
                 SELECT sub_menu_id FROM previlage 
                 WHERE group_id = $user_group_id AND view = 1
             ) 
             ORDER BY m.sort_by ASC, sm.sub_menu_name ASC";
    
    $result = mysqli_query($con, $stmt);
    $menu_items = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $menu_items[] = $row;
    }
    
    return $menu_items;
}

function getUserPermissions($con, $user_group_id) {
    $stmt = "SELECT p.*, sm.sub_menu_name, sm.url 
             FROM previlage p 
             LEFT JOIN submenu sm ON p.sub_menu_id = sm.submenu_id 
             WHERE p.group_id = $user_group_id";
    
    $result = mysqli_query($con, $stmt);
    $permissions = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $permissions[$row['sub_menu_id']] = $row;
    }
    
    return $permissions;
}

function isAdmin($con, $user_group_id) {
    // Check if user is in admin group (assuming group_id 1 is admin)
    return $user_group_id == 1;
}

function requireLogin() {
    if(!isset($_SESSION['uid']) || !isset($_SESSION['usergroup'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit();
    }
}

function requirePermission($con, $sub_menu_id, $action = 'view') {
    requireLogin();
    checkPermission($con, $_SESSION['usergroup'], $sub_menu_id, $action);
}

// Helper function to display permission-based buttons
function displayActionButtons($con, $user_group_id, $sub_menu_id, $edit_url = '', $delete_id = '', $view_url = '') {
    $buttons = '';
    
    if(canView($con, $user_group_id, $sub_menu_id) && !empty($view_url)) {
        $buttons .= '<a href="' . $view_url . '" class="btn btn-info btn-sm" title="View"><i class="fa fa-eye"></i></a> ';
    }
    
    if(canEdit($con, $user_group_id, $sub_menu_id) && !empty($edit_url)) {
        $buttons .= '<a href="' . $edit_url . '" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a> ';
    }
    
    if(canDelete($con, $user_group_id, $sub_menu_id) && !empty($delete_id)) {
        $buttons .= '<button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(' . $delete_id . ')" title="Delete"><i class="fa fa-trash"></i></button>';
    }
    
    return $buttons;
}

// Helper function to check if user can access a page
function canAccessPage($con, $user_group_id, $page_url) {
    $stmt = "SELECT submenu_id FROM submenu WHERE url = '$page_url'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return canView($con, $user_group_id, $row['submenu_id']);
    }
    
    return false;
}
?>

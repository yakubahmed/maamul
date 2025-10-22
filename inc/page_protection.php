<?php
// Page Protection Middleware
// Include this file at the top of pages that need specific permission checking

// Example usage for different pages:

// For Items Management Pages
function protectItemsPage($con, $action = 'view') {
    // Get submenu ID for items (you'll need to set this based on your database)
    $submenu_id = getSubmenuIdByName($con, 'items');
    if($submenu_id) {
        requirePermission($con, $submenu_id, $action);
    }
}

// For Sales/POS Pages
function protectSalesPage($con, $action = 'view') {
    $submenu_id = getSubmenuIdByName($con, 'pos');
    if($submenu_id) {
        requirePermission($con, $submenu_id, $action);
    }
}

// For User Management Pages
function protectUserPage($con, $action = 'view') {
    $submenu_id = getSubmenuIdByName($con, 'users');
    if($submenu_id) {
        requirePermission($con, $submenu_id, $action);
    }
}

// Helper function to get submenu ID by name
function getSubmenuIdByName($con, $menu_name) {
    $stmt = "SELECT submenu_id FROM submenu WHERE sub_menu_name LIKE '%$menu_name%' OR url LIKE '%$menu_name%' LIMIT 1";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['submenu_id'];
    }
    
    return null;
}

// Quick protection functions for common actions
function protectView($con, $page_name) {
    protectPage($con, $page_name, 'view');
}

function protectAdd($con, $page_name) {
    protectPage($con, $page_name, 'add');
}

function protectEdit($con, $page_name) {
    protectPage($con, $page_name, 'edit');
}

function protectDelete($con, $page_name) {
    protectPage($con, $page_name, 'delete');
}

function protectPage($con, $page_name, $action = 'view') {
    $submenu_id = getSubmenuIdByName($con, $page_name);
    if($submenu_id) {
        requirePermission($con, $submenu_id, $action);
    } else {
        // If page not found in submenu, redirect to 403
        header('Location: ' . BASE_URL . '403.php');
        exit();
    }
}
?>




<?php
// Access Control System
// This file should be included in all pages to enforce permission checking

// Include the permissions utility
require_once('permissions.php');

// Function to get submenu ID from current page URL
function getSubmenuIdFromUrl($con, $current_url) {
    // Remove BASE_URL from current URL to get relative path
    $relative_url = str_replace(BASE_URL, '', $current_url);
    
    // Remove query parameters
    $relative_url = strtok($relative_url, '?');
    
    // Remove trailing slash
    $relative_url = rtrim($relative_url, '/');
    
    // If it's the root page, check for dashboard
    if(empty($relative_url) || $relative_url == 'index.php') {
        $relative_url = 'index.php';
    }
    
    $stmt = "SELECT submenu_id FROM submenu WHERE url = '$relative_url'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['submenu_id'];
    }
    
    return null;
}

// Function to check if current page requires permission
function requiresPermission($current_url) {
    // Pages that don't require permission checking
    $public_pages = array(
        'login.php',
        'logout.php',
        'forgot-password.php',
        'reset-password.php',
        'new-password.php',
        '404.php',
        '403.php'
    );
    
    $relative_url = str_replace(BASE_URL, '', $current_url);
    $relative_url = strtok($relative_url, '?');
    $relative_url = rtrim($relative_url, '/');
    
    if(empty($relative_url)) {
        $relative_url = 'index.php';
    }
    
    return !in_array($relative_url, $public_pages);
}

// Main access control function
function enforceAccessControl($con) {
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['usergroup'])) {
        // If trying to access a protected page, redirect to login
        if(requiresPermission($_SERVER['REQUEST_URI'])) {
            header('Location: ' . BASE_URL . 'login.php');
            exit();
        }
        return; // Allow access to public pages
    }
    
    // If page requires permission checking
    if(requiresPermission($_SERVER['REQUEST_URI'])) {
        $submenu_id = getSubmenuIdFromUrl($con, $_SERVER['REQUEST_URI']);
        
        if($submenu_id === null) {
            // Page not found in submenu table, allow access (might be a special page)
            return;
        }
        
        // Check if user has view permission for this page
        if(!canView($con, $_SESSION['usergroup'], $submenu_id)) {
            header('Location: ' . BASE_URL . '403.php');
            exit();
        }
    }
}

// Function to check action permissions (for AJAX requests)
function checkActionPermission($con, $action, $submenu_id = null) {
    if(!isset($_SESSION['uid']) || !isset($_SESSION['usergroup'])) {
        return false;
    }
    
    if($submenu_id === null) {
        // Try to get submenu_id from referer
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $submenu_id = getSubmenuIdFromUrl($con, $referer);
    }
    
    if($submenu_id === null) {
        return false;
    }
    
    switch($action) {
        case 'view':
            return canView($con, $_SESSION['usergroup'], $submenu_id);
        case 'add':
            return canAdd($con, $_SESSION['usergroup'], $submenu_id);
        case 'edit':
            return canEdit($con, $_SESSION['usergroup'], $submenu_id);
        case 'delete':
            return canDelete($con, $_SESSION['usergroup'], $submenu_id);
        default:
            return false;
    }
}

// Function to secure AJAX endpoints
function secureAjaxEndpoint($con, $required_action = 'view', $submenu_id = null) {
    if(!checkActionPermission($con, $required_action, $submenu_id)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Access denied'));
        exit();
    }
}

// Auto-enforce access control when this file is included (only for web requests)
if(isset($con) && isset($_SERVER['REQUEST_URI']) && !defined('CLI_MODE')) {
    enforceAccessControl($con);
}
?>

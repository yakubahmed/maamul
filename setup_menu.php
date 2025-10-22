<?php
// Script to add new menu items for user management
include('inc/config.php');

// Add menu items for user management
$menu_items = [
    [
        'menu_name' => 'User Groups',
        'sub_menu_name' => 'User Groups',
        'url' => 'user/groups.php',
        'menu_icon' => 'fas fa-users',
        'sort_by' => 10
    ],
    [
        'menu_name' => 'Permissions',
        'sub_menu_name' => 'Permissions',
        'url' => 'user/permissions.php',
        'menu_icon' => 'fas fa-key',
        'sort_by' => 11
    ]
];

foreach($menu_items as $item) {
    // Check if menu exists
    $stmt = "SELECT menu_id FROM menu WHERE menu_name = '{$item['menu_name']}'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $menu_id = $row['menu_id'];
    } else {
        // Create menu
        $stmt = "INSERT INTO menu (menu_name, menu_icon, sort_by) VALUES ('{$item['menu_name']}', '{$item['menu_icon']}', {$item['sort_by']})";
        mysqli_query($con, $stmt);
        $menu_id = mysqli_insert_id($con);
    }
    
    // Check if submenu exists
    $stmt = "SELECT submenu_id FROM submenu WHERE sub_menu_name = '{$item['sub_menu_name']}'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) == 0) {
        // Create submenu
        $stmt = "INSERT INTO submenu (menu_id, sub_menu_name, url) VALUES ($menu_id, '{$item['sub_menu_name']}', '{$item['url']}')";
        mysqli_query($con, $stmt);
        $submenu_id = mysqli_insert_id($con);
        
        // Give admin group (group_id = 1) full permissions
        $stmt = "INSERT INTO previlage (group_id, sub_menu_id, date, view, add, edit, delete) VALUES (1, $submenu_id, NOW(), 1, 1, 1, 1)";
        mysqli_query($con, $stmt);
        
        echo "Added: {$item['sub_menu_name']}<br>";
    } else {
        echo "Already exists: {$item['sub_menu_name']}<br>";
    }
}

echo "Menu setup completed!";
?>




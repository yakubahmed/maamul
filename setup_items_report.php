<?php
// Script to add Items Inventory Report to the menu system
include('inc/config.php');

// Check if "Reports" menu exists, if not create it
$stmt = "SELECT menu_id FROM menu WHERE menu_name = 'Reports'";
$result = mysqli_query($con, $stmt);

if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $menu_id = $row['menu_id'];
    echo "Reports menu already exists (ID: $menu_id)<br>";
} else {
    // Create Reports menu
    $stmt = "INSERT INTO menu (menu_name, menu_icon, sort_by) VALUES ('Reports', 'fas fa-chart-bar', 20)";
    mysqli_query($con, $stmt);
    $menu_id = mysqli_insert_id($con);
    echo "Created Reports menu (ID: $menu_id)<br>";
}

// Check if "Items Inventory Report" submenu exists
$stmt = "SELECT submenu_id FROM submenu WHERE sub_menu_name = 'Items Inventory Report'";
$result = mysqli_query($con, $stmt);

if(mysqli_num_rows($result) == 0) {
    // Create submenu
    $stmt = "INSERT INTO submenu (menu_id, sub_menu_name, url) VALUES ($menu_id, 'Items Inventory Report', 'reports/items-inventory.php')";
    mysqli_query($con, $stmt);
    $submenu_id = mysqli_insert_id($con);
    
    // Give all user groups view permission for the report
    $stmt = "SELECT group_id FROM usergroup";
    $result = mysqli_query($con, $stmt);
    
    while($row = mysqli_fetch_assoc($result)) {
        $group_id = $row['group_id'];
        $stmt2 = "INSERT INTO previlage (group_id, sub_menu_id, date, view, add, edit, delete) VALUES ($group_id, $submenu_id, NOW(), 1, 0, 0, 0)";
        mysqli_query($con, $stmt2);
    }
    
    echo "Added: Items Inventory Report (ID: $submenu_id)<br>";
} else {
    echo "Items Inventory Report already exists<br>";
}

echo "Menu setup completed!";
?>




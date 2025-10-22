<?php

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Mogadishu');

if(isset($_POST['role'])){
    $role = $_POST['role'];
    $i=0;
    $stmt = "SELECT * from menu WHERE menu_id in (SELECT menu_id FROM submenu WHERE submenu_id IN(
    SELECT sub_menu_id FROM previlage))";
    $result = mysqli_query($con, $stmt); 
    while($row = mysqli_fetch_assoc($result)){
        $id = $row['menu_id'];
        $name = $row['menu_name'];
        $icon = $row['menu_icon'];
        $i++;

        echo "
            <tr>
                <td>$i</td>
                <td>$name</td>
                <td>
                <span class='badge badge-secondary'>
                View <input type='checkbox' name='' id=''>
                </span>

                <span class='badge badge-secondary'>
                Add <input type='checkbox' name='' id=''>
                </span>

                <span class='badge badge-secondary'>
                Edit <input type='checkbox' name='' id=''>
                </span>

                <span class='badge badge-secondary'>
                Delete <input type='checkbox' name='' id=''>
                </span>
                </td>
            </tr>
        ";
    
    }
}


?>
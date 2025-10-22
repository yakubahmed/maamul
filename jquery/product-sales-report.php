<?php 

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['fdate'])){
    $sdate = $_POST['fdate'];
    $fdate = $_POST['tdate'];
    $i=0;
        $stmt = "SELECT item_name, item_image, COUNT(order_item.item_id), orders.order_date, unit.unit_name, unit.shortname
         FROM item, order_item, orders, unit WHERE item.item_id = order_item.item_id AND orders.order_id = order_item.order_id
         AND item.unit = unit.unit_id AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' GROUP BY (order_item.item_id)";
        $result = mysqli_query($con, $stmt);
        
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            while($row = mysqli_fetch_array($result)){
                //$date = date('d-m-Y', strtotime($row['order_date']));
                $item = $row[0];
                $salescount = $row[2];
                //$uname = $row[3];
                $short = $row[5];
                $i++;
                echo "
                    <tr>
                        <td>$i</td>
                        <td>$item</td>
                        <td>$salescount $short</td>
                        
                    </tr>
                ";
            }

            $stmt = "SELECT COUNT(order_item.item_id), orders.order_date FROM order_item, orders WHERE
            orders.order_id = order_item.order_id AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'  ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($result)){
                $t = $row[0];
              

                if(empty($t)){$t = 0;}
                

                echo "
                    <tr>
                        <th colspan='2' class='text-right'> Total sales count </th>
                        <th > $t </th>
                        
                    </tr>  
                ";
            }
        }
    
}

?>
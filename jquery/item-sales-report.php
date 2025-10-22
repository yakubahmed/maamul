<?php
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['fdate'])){
    $sdate = $_POST['fdate'];
    $fdate = $_POST['tdate'];
    $item = $_POST['item'];

    function cust($id){
        global $con; 
        $stmt ="SELECT * FROM customer WHERE customer_id IN (SELECT cust_id FROM orders WHERE order_id = $id) ";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['cust_name'];
    }

    if(empty($item)){
        $stmt = "SELECT orders.order_id,orders.ser, order_item.order_item_id, SUM( order_item.qty), SUM(order_item.sub_total), item.item_name, orders.order_date 
        FROM orders, order_item, item WHERE orders.order_id = order_item.order_id
         AND order_item.item_id = item.item_id AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' GROUP BY order_item.item_id  ";
         $result = mysqli_query($con, $stmt);
         while($row = mysqli_fetch_array($result)){
             $id = $row[0];
             $ser = $row[1];
             $item_count = $row[3];
             $amount = $row[4];
             $name = $row[5];
             $date = date('d-m-Y', strtotime($row[6]));
            $customer = cust($id);
             echo "
                <tr>
                    <td>$ser</td>
                    <td>$date</td>
                    <td>$customer</td>
                    <td>$name</td>
                    <td>$item_count</td>
                    <td>$amount $</td>
                </tr>
             ";
         }

         $stmt = "SELECT SUM(order_item.qty), SUM(order_item.sub_total), orders.order_id
         FROM order_item, orders WHERE orders.order_id = order_item.order_id AND 
         orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'   ";
         $result = mysqli_query($con, $stmt);
         while($row = mysqli_fetch_array($result)){
             $tqty = $row[0];
             $tamount = $row[1];

             echo "
                <tr>
                    <td colspan='4' class='text-right'> Total</td>
                    <td>$tqty</td>
                    <td>$tamount</td>
                </tr>
             ";
         }

    }else{
        $stmt = "SELECT orders.order_id,orders.ser, order_item.order_item_id, SUM( order_item.qty), SUM(order_item.sub_total), item.item_name, orders.order_date 
        FROM orders, order_item, item WHERE orders.order_id = order_item.order_id
         AND order_item.item_id = item.item_id AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' 
         AND order_item.item_id = $item GROUP BY order_item.item_id  ";
         $result = mysqli_query($con, $stmt);
         while($row = mysqli_fetch_array($result)){
             $id = $row[0];
             $ser = $row[1];
             $item_count = $row[3];
             $amount = $row[4];
             $name = $row[5];
             $date = date('d-m-Y', strtotime($row[6]));
            $customer = cust($id);
             echo "
                <tr>
                    <td>$ser</td>
                    <td>$date</td>
                    <td>$customer</td>
                    <td>$name</td>
                    <td>$item_count</td>
                    <td>$amount $</td>
                </tr>
             ";
         }

         $stmt = "SELECT SUM(order_item.qty), SUM(order_item.sub_total), orders.order_id
         FROM order_item, orders WHERE orders.order_id = order_item.order_id AND 
         orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' AND order_item.item_id = $item   ";
         $result = mysqli_query($con, $stmt);
         while($row = mysqli_fetch_array($result)){
             $tqty = $row[0];
             $tamount = $row[1];

             echo "
                <tr>
                    <td colspan='4' class='text-right'> Total</td>
                    <td>$tqty</td>
                    <td>$tamount</td>
                </tr>
             ";
         }
    }
}
?>
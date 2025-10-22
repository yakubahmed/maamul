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
        $stmt ="SELECT * FROM supplier WHERE supp_id IN (SELECT supp_id FROM purchase WHERE purchase_id = $id) ";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['sup_name'];
    }

    if(empty($item)){
        $stmt = "SELECT purchase.purchase_id,purchase.ser, pur_items.pur_iem_id, SUM( pur_items.qty), SUM(pur_items.sub_total), item.item_name, purchase.pur_date 
        FROM purchase, pur_items, item WHERE purchase.purchase_id = pur_items.purchase_id
         AND pur_items.item_id = item.item_id AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate' GROUP BY pur_items.item_id  ";
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

         $stmt = "SELECT SUM(pur_items.qty), SUM(pur_items.sub_total), purchase.purchase_id
         FROM pur_items, purchase WHERE purchase.purchase_id = pur_items.purchase_id AND 
         purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'   ";
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
        $stmt = "SELECT purchase.purchase_id,purchase.ser, pur_items.pur_items_id, SUM( pur_items.qty), SUM(pur_items.sub_total), item.item_name, purchase.pur_date 
        FROM purchase, pur_items, item WHERE purchase.purchase_id = pur_items.purchase_id
         AND pur_items.item_id = item.item_id AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate' 
         AND pur_items.item_id = $item GROUP BY pur_items.item_id  ";
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

         $stmt = "SELECT SUM(pur_items.qty), SUM(pur_items.sub_total), purchase.purchase_id
         FROM pur_items, purchase WHERE purchase.purchase_id = pur_items.purchase_id AND 
         purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate' AND pur_items.item_id = $item   ";
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
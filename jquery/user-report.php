<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['fdate'])){
    $fdate = mysqli_real_escape_string($con, $_POST['fdate']);
    $tdate = mysqli_real_escape_string($con, $_POST['tdate']);
    $user = mysqli_real_escape_string($con, $_POST['user']);

    function sales($id, $fdate, $sdate){
        global $con;
        $stmt = "SELECT COUNT(*) FROM orders WHERE order_by = $id AND order_date >= '$fdate' AND order_date <= '$sdate'";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_array($result)){
            return $row[0];
        }
    }

    function purchase($id, $fdate, $sdate){
        global $con;
        $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_by = $id AND pur_date >= '$fdate' AND pur_date <= '$sdate'";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_array($result)){
            return $row[0];
        }
    }

    function expense($id, $fdate, $sdate){
        global $con;
        $stmt = "SELECT COUNT(*) FROM expense WHERE reg_by = $id AND reg_date >= '$fdate' AND reg_date <= '$sdate'";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_array($result)){
            return $row[0];
        }
    }

    $i=0;
    $stmt = "SELECT * FROM users order by fullname ASC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $i++;
        $id = $row['userid'];
        $name = $row['fullname'];
        $phone = $row['phone_number'];
        $sale = sales($id, $fdate, $tdate);
        $pur = purchase($id, $fdate, $tdate);
        $expense = expense($id, $fdate, $tdate);
        echo "
            <tr>
                <td>$i</td>
                <td>$name ($phone)</td>
                <td> <a href='#' data-toggle='modal' data-target='#salesModal'>$sale</a> </td>
                <td><a href='#' data-toggle='modal' data-target='#purchaseModal'>$pur</a> </td>
                <td><a href='#' data-toggle='modal' data-target='#expenseModal'>$expense</a></td>
            </tr>
        ";
    }



}

?>
<?php 

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['fdate'])){
    $sdate = $_POST['fdate'];
    $fdate = $_POST['tdate'];
    $status = $_POST['pstatus'];
    $customer = $_POST['customer'];

    if(empty($customer) && empty($status)){
        $stmt = "
        SELECT purchase.purchase_id, purchase.ser, purchase.pur_date, supplier.sup_name, 
        supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal, purchase.balance, purchase.payment_status 
        FROM purchase, supplier WHERE purchase.supp_id  = supplier.supp_id  AND purchase.pur_status != 'on-going' AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'
         ORDER BY purchase.purchase_id DESC ";
        $result = mysqli_query($con, $stmt);
        
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['purchase_id'];
                $ser = $row['ser'];
                $date = date('d-m-Y', strtotime($row['pur_date'])) ;
                $cust = $row['sup_name'];
                $cphone = $row['phone_num'];
                $ostatus = $row['pur_status'];
                $pamount = $row['paid_amount'];
                $balance = $row['balance'];
                $tamount = $row['gtotal'];
                $pstatus = $row['payment_status'];

                echo "
                    <tr>
                        <td>$ser</td>
                        <td>$date</td>
                        <td>$cust - $cphone</td>
                        <td>$tamount</td>
                        <td>$pamount</td>
                        <td>$balance</td>
                    </tr>
                ";
            }

            $stmt = "SELECT SUM(gtotal), SUM(paid_amount), SUM(balance) FROM purchase WHERE
            purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'  ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($result)){
                $t = $row[0];
                $a = $row[1];
                $b = $row[2];

                if(empty($t)){$t = 0.00;}
                if(empty($a)){$a = 0.00;}
                if(empty($b)){$b = 0.00;}

                echo "
                    <tr>
                        <th colspan='3' class='text-right'> Total </th>
                        <th > $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else if(empty($customer) && !empty($status)){
        $stmt = "
        SELECT purchase.purchase_id, purchase.ser, purchase.pur_date, supplier.sup_name, 
        supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal, purchase.balance, purchase.payment_status 
        FROM purchase, supplier WHERE purchase.supp_id = supplier.supp_id AND purchase.pur_status != 'on-going' AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'
        AND purchase.payment_status = '$status' ORDER BY purchase.purchase_id DESC ";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['purchase_id'];
                $ser = $row['ser'];
                $date = date('d-m-Y', strtotime($row['pur_date'])) ;
                $cust = $row['sup_name'];
                $cphone = $row['phone_num'];
                $ostatus = $row['pur_status'];
                $pamount = $row['paid_amount'];
                $balance = $row['balance'];
                $tamount = $row['gtotal'];
                $pstatus = $row['payment_status'];

                echo "
                    <tr>
                        <td>$ser</td>
                        <td>$date</td>
                        <td>$cust - $cphone</td>
                        <td>$tamount</td>
                        <td>$pamount</td>
                        <td>$balance</td>
                    </tr>
                ";
            }
            $stmt = "SELECT SUM(gtotal), SUM(paid_amount), SUM(balance) FROM purchase WHERE
            purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate' AND payment_status = '$status' ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($result)){
                $t = $row[0];
                $a = $row[1];
                $b = $row[2];

                if(empty($t)){$t = 0.00;}
                if(empty($a)){$a = 0.00;}
                if(empty($b)){$b = 0.00;}

                echo "
                    <tr>
                        <th colspan='3' class='text-right'> Total </th>
                        <th > $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else if(empty($status) && !empty($customer)){
        $stmt = "
        SELECT purchase.purchase_id, purchase.ser, purchase.pur_date, supplier.sup_name, 
        supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal, purchase.balance, purchase.payment_status 
        FROM purchase, supplier WHERE purchase.supp_id = supplier.supp_id AND purchase.pur_status != 'on-going' AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'
        AND supplier.supp_id = $customer ORDER BY purchase.purchase_id DESC ";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['purchase_id'];
                $ser = $row['ser'];
                $date = date('d-m-Y', strtotime($row['pur_date'])) ;
                $cust = $row['sup_name'];
                $cphone = $row['phone_num'];
                $ostatus = $row['pur_status'];
                $pamount = $row['paid_amount'];
                $balance = $row['balance'];
                $tamount = $row['gtotal'];
                $pstatus = $row['payment_status'];

                echo "
                    <tr>
                        <td>$ser</td>
                        <td>$date</td>
                        <td>$cust - $cphone</td>
                        <td>$tamount</td>
                        <td>$pamount</td>
                        <td>$balance</td>
                    </tr>
                ";
            }

            $stmt = "SELECT SUM(gtotal), SUM(paid_amount), SUM(balance) FROM purchase WHERE
            purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate' AND supp_id = $customer  ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($result)){
                $t = $row[0];
                $a = $row[1];
                $b = $row[2];

                if(empty($t)){$t = 0.00;}
                if(empty($a)){$a = 0.00;}
                if(empty($b)){$b = 0.00;}

                echo "
                    <tr>

                        <th colspan='3' class='text-right'> $t </th>
                        <th > $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else{
        $stmt = "
        SELECT purchase.purchase_id, purchase.ser, purchase.pur_date, supplier.sup_name, 
        supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal, purchase.balance, purchase.payment_status 
        FROM purchase, supplier WHERE purchase.supp_id = supplier.supp_id AND purchase.pur_status != 'on-going' AND purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'
        AND purchase.payment_status = '$status' AND supplier.supp_id = $customer ORDER BY purchase.order_id DESC ";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['order_id'];
                $ser = $row['ser'];
                $date = date('d-m-Y', strtotime($row['pur_date'])) ;
                $cust = $row['sup_name'];
                $cphone = $row['phone_num'];
                $ostatus = $row['pur_status'];
                $pamount = $row['paid_amount'];
                $balance = $row['balance'];
                $tamount = $row['gotal'];
                $pstatus = $row['payment_status'];

                echo "
                    <tr>
                        <td>$ser</td>
                        <td>$date</td>
                        <td>$cust - $cphone</td>
                        <td>$tamount</td>
                        <td>$pamount</td>
                        <td>$balance</td>
                    </tr>
                ";
            }
            $stmt = "SELECT SUM(gtotal), SUM(paid_amount), SUM(balance) FROM purchase WHERE
            purchase.pur_date >= '$sdate' AND purchase.pur_date <= '$fdate'  
            AND supp_id = $customer AND payment_status = '$status'";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_array($result)){
                $t = $row[0];
                $a = $row[1];
                $b = $row[2];

                if(empty($t)){$t = 0.00;}
                if(empty($a)){$a = 0.00;}
                if(empty($b)){$b = 0.00;}

                echo "
                    <tr>
                        <th colspan='3' class='text-right'> Total: </th>
                        <th> $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }
}

?>
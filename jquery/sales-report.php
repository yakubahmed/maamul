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
        SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
        customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
        FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'
         ORDER BY orders.order_id DESC ";
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
                $date = date('d-m-Y', strtotime($row['order_date'])) ;
                $cust = $row['cust_name'];
                $cphone = $row['cust_phone'];
                $ostatus = $row['order_status'];
                $pamount = $row['amount'];
                $balance = $row['balance'];
                $tamount = $row['pr_af_dis'];
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

            $stmt = "SELECT SUM(pr_af_dis), SUM(amount), SUM(balance) FROM orders WHERE
            orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'  ";
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
                        <th colspan='4' class='text-right'> $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else if(empty($customer) && !empty($status)){
        $stmt = "
        SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
        customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
        FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'
        AND orders.payment_status = '$status' ORDER BY orders.order_id DESC ";
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
                $date = date('d-m-Y', strtotime($row['order_date'])) ;
                $cust = $row['cust_name'];
                $cphone = $row['cust_phone'];
                $ostatus = $row['order_status'];
                $pamount = $row['amount'];
                $balance = $row['balance'];
                $tamount = $row['pr_af_dis'];
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
            $stmt = "SELECT SUM(pr_af_dis), SUM(amount), SUM(balance) FROM orders WHERE
            orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' AND payment_status = '$status' ";
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
                        <th colspan='4' class='text-right'> $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else if(empty($status) && !empty($customer)){
        $stmt = "
        SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
        customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
        FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'
        AND customer.customer_id = $customer ORDER BY orders.order_id DESC ";
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
                $date = date('d-m-Y', strtotime($row['order_date'])) ;
                $cust = $row['cust_name'];
                $cphone = $row['cust_phone'];
                $ostatus = $row['order_status'];
                $pamount = $row['amount'];
                $balance = $row['balance'];
                $tamount = $row['pr_af_dis'];
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

            $stmt = "SELECT SUM(pr_af_dis), SUM(amount), SUM(balance) FROM orders WHERE
            orders.order_date >= '$sdate' AND orders.order_date <= '$fdate' AND customer = $customer  ";
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
                        <th colspan='4' class='text-right'> $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }else{
        $stmt = "
        SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
        customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
        FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' AND orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'
        AND orders.payment_status = '$status' AND customer.customer_id = $customer ORDER BY orders.order_id DESC ";
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
                $date = date('d-m-Y', strtotime($row['order_date'])) ;
                $cust = $row['cust_name'];
                $cphone = $row['cust_phone'];
                $ostatus = $row['order_status'];
                $pamount = $row['amount'];
                $balance = $row['balance'];
                $tamount = $row['pr_af_dis'];
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
            $stmt = "SELECT SUM(pr_af_dis), SUM(amount), SUM(balance) FROM orders WHERE
            orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'  
            AND customer_id = $customer AND payment_status = '$status'";
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
                        <th colspan='4' class='text-right'> $t </th>
                        <th > $a </th>
                        <th > $b </th>
                    </tr>  
                ";
            }
        }
    }
}

?>
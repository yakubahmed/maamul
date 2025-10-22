<?php 

session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['cust'])){

    $status = $_POST['pstatus'];
    $customer = $_POST['customer'];


        $stmt = "SELECT * FROM customer WHERE customer_id IN (SELECT cust_id FROM orders)";
        $result = mysqli_query($con, $stmt);
        
        if(mysqli_num_rows($result) < 1){
            echo "
                <tr>
                    <td colspan='6' class='text-center'>  <strong> No record found </strong>  </td>
                </tr>
            ";
        }else{
            $i = 0;
            while($row = mysqli_fetch_assoc($result)){
                $i++;
               $id = $row['customer_id'];
                $cust = $row['cust_name'];
                $cphone = $row['cust_phone'];
              
                $tamount = get_tamout($id);
                $balance = get_pamout($id);
                $pamount = get_balance($id);


                echo "
                    <tr>
                        <td>$i</td>
                        <td>$cust</td>
                        <td>$tamount</td>
                        <td>$pamount</td>
                        <td>$balance</td>
                    </tr>
                ";
            }

            // $stmt = "SELECT SUM(pr_af_dis), SUM(amount), SUM(balance) FROM orders WHERE
            // orders.order_date >= '$sdate' AND orders.order_date <= '$fdate'  ";
            // $result = mysqli_query($con, $stmt);
            // while($row = mysqli_fetch_array($result)){
            //     $t = $row[0];
            //     $a = $row[1];
            //     $b = $row[2];

            //     if(empty($t)){$t = 0.00;}
            //     if(empty($a)){$a = 0.00;}
            //     if(empty($b)){$b = 0.00;}

            //     echo "
            //         <tr>
            //             <th colspan='4' class='text-right'> $t </th>
            //             <th > $a </th>
            //             <th > $b </th>
            //         </tr>  
            //     ";
            // }
        }
    
}



function get_tamout($id){
    global $con;
    $stmt = "SELECT SUM(pr_af_dis) FROM orders WHERE cust_id = $id";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    if(empty($row[0])){$row[0] = 0;} 

    return $row[0];
}

function get_pamout($id){
    global $con;
    $stmt = "SELECT SUM(amount) FROM orders WHERE cust_id = $id";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    if(empty($row[0])){$row[0] = 0;} 

    return $row[0];
}

function get_balance($id){
    global $con;
    $stmt = "SELECT SUM(balance) FROM orders WHERE cust_id = $id";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    if(empty($row[0])){$row[0] = 0;} 
    return $row[0];
}

?>
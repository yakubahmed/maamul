<?php 
// Start output buffering to prevent any extra output
ob_start();

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

// Handle get_customer request
if(isset($_POST['get_customer'])){
    $customer_id = mysqli_real_escape_string($con, $_POST['get_customer']);
    
    $stmt = "SELECT * FROM customer WHERE customer_id = $customer_id";
    $result = mysqli_query($con, $stmt);
    
    if($result && mysqli_num_rows($result) > 0){
        $customer = mysqli_fetch_assoc($result);
        header('Content-Type: application/json');
        echo json_encode($customer);
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Customer not found'));
    }
    exit;
}

if(isset($_POST['cname'])){
    $cname = mysqli_real_escape_string($con, $_POST['cname']);
    $phone  = mysqli_real_escape_string($con, $_POST['cphone'] ) ;
    $email  = mysqli_real_escape_string($con, $_POST['cemail'] ) ;
    $addr  = mysqli_real_escape_string($con, $_POST['addr'] ) ;
    $status  = mysqli_real_escape_string($con, $_POST['status'] ) ;
    $balance  = mysqli_real_escape_string($con, $_POST['balance'] ) ;
    $id = $_SESSION['uid'];

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');


    $stmt = "SELECT * FROM customer WHERE cust_phone = '$phone' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        ob_clean();
        echo "phone_found";
        exit;
    }else{
        if(empty($email)){
            $stmt = "SELECT COUNT(*) FROM customer "; 
            $result = mysqli_query($con, $stmt); 
            $row = mysqli_fetch_array($result);
            $serial  = "CUST-" . 0000 . $row[0] + 1 ;

            $stmt = "INSERT INTO customer(cust_serial, cust_name, cust_phone, cust_addr, cust_email, status, reg_date, reg_by, warehouse_id)";
            $stmt .= " VALUES ('$serial', '$cname', '$phone', '$addr', '$email', '$status', '$date', '$userid', 1)";
            $result = mysqli_query($con, $stmt); 
            if($result){

                $cust_id = mysqli_insert_id($con);
             if(!empty($balance) && $balance != 0){
         
                 $index = $cust_id;
                 $prefix = 'BAL-';
                 $serial =  sprintf("%s%03s", $prefix, $index);
             
         
                 $stmt = "INSERT INTO orders (cust_id, ser, order_status, order_date,amount,pr_be_dis	, pr_af_dis,	 balance, order_by, trans_date, warehouse, payment_status) ";
                 $stmt .= "VALUES ( $cust_id,'$serial' ,'Confirmed','$date', 0, $balance, $balance, $balance, $id, '$date', 1, 'Not paid' )";
                 $result = mysqli_query($con, $stmt); 
                  
                }

                // Clean output and return success
                ob_clean();
                echo 'success';
                exit;

             } else {
                ob_clean();
                echo 'database_error: ' . mysqli_error($con);
                exit;
             }
        }else{
            $stmt = "SELECT * FROM customer WHERE cust_email = '$email'";
            $result = mysqli_query($con, $stmt); 
            if(mysqli_num_rows($result) > 0){
                ob_clean();
                echo "found_email";
                exit;
            }else{
               $stmt = "SELECT COUNT(*) FROM customer "; 
               $result = mysqli_query($con, $stmt); 
               $row = mysqli_fetch_array($result);
               $serial  = "CUST-" . 0000 . $row[0] + 1 ;
    
               $stmt = "INSERT INTO customer(cust_serial, cust_name, cust_phone, cust_addr, cust_email, status, reg_date, reg_by, warehouse_id)";
               $stmt .= " VALUES ('$serial', '$cname', '$phone', '$addr', '$email', '$status', '$date', '$userid', 1)";
               $result = mysqli_query($con, $stmt); 
               if($result){
    
                   $cust_id = mysqli_insert_id($con);
                if(!empty($balance) && $balance != 0){
            
                    $index = $cust_id;
                    $prefix = 'BAL-';
                    $serial =  sprintf("%s%03s", $prefix, $index);
                
            
                    $stmt = "INSERT INTO orders (cust_id, ser, order_status, order_date,amount,pr_be_dis	, pr_af_dis,	 balance, order_by, trans_date, warehouse, payment_status) ";
                    $stmt .= "VALUES ( $cust_id,'$serial' ,'Confirmed','$date', 0, $balance, $balance, $balance, $id, '$date', 1, 'Not paid' )";
                    $result = mysqli_query($con, $stmt); 
                     
                   }

                   // Clean output and return success
                   ob_clean();
                   echo 'success';
                   exit;

                } else {
                   ob_clean();
                   echo 'database_error: ' . mysqli_error($con);
                   exit;
                }
    
            }

        }
    }


}

//Delete customer
if(isset($_POST['del_cust'])){
    $id = $_POST['del_cust'];
    $stmt = "DELETE FROM customer WHERE customer_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo "deleted";
    }
}


if(isset($_POST['getCustomers'])){

    function get_balance($id){
        global $con; 
        $stmt = "SELECT SUM(balance) FROM orders WHERE cust_id = $id ";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        if(empty($row[0])){
          return "0.00";
        }else{
          return $row[0];
        }
      }

    $i = 0;
    $stmt = "SELECT * FROM customer ORDER by customer_id DESC";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_assoc($result)){
            $i++;
            $cust_id = $row['customer_id'];
            $name = $row['cust_name'];
            $phone = $row['cust_phone'];
            $status = $row['status'];
            $date = date("M d, Y", strtotime($row['reg_date']));

            if($status == "Active"){
                $status = " <span class='badge badge-subtle badge-success'>$status</span> ";
            }else{
                $status = " <span class='badge badge-subtle badge-danger'>$status</span> ";
            }
    
            $balance = get_balance($cust_id);

            echo "
                <tr>
                    <td>$i</td>
                    <td>$name</td>
                    <td>$phone</td>
                    <td>$balance $</td>
                    <td> <p>$status</p> </td>
                    <td>$date</td>
                    <td>
                        <div class='btn-group btn-group-toggle' data-toggle='buttons'>
                        <button type='button' class='btn btn-info btn-sm'  data-toggle='modal' data-target='#custDetailModal' data-toggle='tooltip' data-placement='top' title='View customer detail' id='view_cust' data-id='$cust_id'> <i class='fa fa-eye'></i> </button>
                        <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editCustModal' data-toggle='tooltip' data-placement='top' title='Edit customer detail' id='edit_cust' data-id='$cust_id'> <i class='fa fa-edit'></i> </button>
                        <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete customer' id='del_cust' data-id='$cust_id'> <i class='fa fa-trash'></i></button>
                        </div>
    
    
                    </td>
                </tr>
            ";
        }
    }else{
        echo "
            <tr> 
                <td colspan='7'> <center>No data available in table</center> </td>
            </tr>
        ";
    }
}


if(isset($_POST['viewSingleCust'])){
    $id = $_POST['viewSingleCust'];

    function get_balance($id){
        global $con; 
        $stmt = "SELECT SUM(balance) FROM orders WHERE cust_id = $id ";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        if(empty($row[0])){
          return "0.00";
        }else{
          return $row[0];
        }
    }

    $i = 0;
    $stmt = "SELECT customer.*, users.fullname, warehouse.name from customer, users, warehouse 
    WHERE customer.reg_by = users.userid and customer.warehouse_id = warehouse.warehouseid AND customer_id = $id";
    $result = mysqli_query($con, $stmt); 

    while($row = mysqli_fetch_assoc($result)){
        $i++;
        $cust_id = $row['customer_id'];
        $name = $row['cust_name'];
        $phone = $row['cust_phone'];
        $status = $row['status'];
        $date = date("M d, Y", strtotime($row['reg_date']));
        $username = $row['fullname'];
        $email = $row['cust_email'];
        $warehouse = $row['name'];
        $addr = $row['cust_addr'];

        $balance = get_balance($cust_id);

        if(empty($addr)){ $addr = "N/A"; }
        if(empty($email)){ $addr = "N/A"; }

        if($status == "Active"){
            $status = " <span class='badge badge-subtle badge-success'>$status</span> ";
        }else{
            $status = " <span class='badge badge-subtle badge-danger'>$status</span> ";
        }

        
        echo"

        <div class='row'>
            <div class='col-md-12'>
            <hr>
            </div>
            <div class='col-md-4'>
                <label > <strong>Magaca</strong> </label>
                <p>$name</p>
            </div>

            <div class='col-md-4'>
                <label > <strong>Taleefan</strong> </label>
                <p>$phone</p>
            </div>

            <div class='col-md-4'>
                <label > <strong>Email Address</strong> </label>
                <p>$email</p>
            </div>

            <div class='col-md-12'>
            <hr>
            </div>

            <div class='col-md-4'>
                <label > <strong>Taarikh-da la diiwan geliyay</strong> </label>
                <p>$date</p>
            </div>

            <div class='col-md-4'>
                <label > <strong>Goobta / Deegan </strong> </label>
                <p>$addr</p>
            </div>

            <div class='col-md-4'>
                <label > <strong>Qofka diiwan geliyay by</strong> </label>
                <p>$username</p>
            </div>

            <div class='col-md-12'>
            <hr>
            </div>

            <div class='col-md-4'>
                <label > <strong>Haraa / Lagu leeyahay</strong> </label>
                <p>$balance $</p>
            </div>

            

            

        </div>

        ";
    }
    
}

if(isset($_POST['editSingleCust'])){
    $id = $_POST['editSingleCust'];
    $stmt = "SELECT * FROM customer WHERE customer_id = $id";
    $result = mysqli_query($con, $stmt); 
    while($row = mysqli_fetch_assoc($result)){
        $cust_id = $row['customer_id'];
        $name = $row['cust_name'];
        $phone = $row['cust_phone'];
        $status = $row['status'];
        $email = $row['cust_email'];
        $addr = $row['cust_addr'];

        if($status == 'Active'){
            $status = "
                <select name='status1' id='status1' class='form-control'>
                    <option value='Active'>Active</option>
                    <option value='Disable'>Disable</option>
                </select>
            ";
        }else{
            $status = "
            <select name='status1' id='status1' class='form-control'>
                <option value='Disable'>Disable</option>
                <option value='Active'>Active</option>
            </select>
        ";
        }

        echo "
        

        <div class='form-group col-md-6'>
          <label for=''> Magaca macaamiilka *</label>
          <input type='hidden' name='custid' id='custid' value='$cust_id'>
          <input type='text' name='cname1' id='cname1' value='$name' class='form-control rounded-0' required>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Taleefon </label>
          <input type='text' name='cphone1' id='cphone1' value='$phone' class='form-control  rounded-0' required>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Email Address</label>
          <input type='text' name='cemail1' id='cemail1' value='$email' class='form-control rounded-0'>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Status *</label>
          $status
        </div>

        <div class='form-group col-md-12'>
          <label for=''> Goobta / Deegan </label>
          <textarea name='addr1' id='addr1' class='form-control rounded-0'>$addr</textarea>
        </div>

        <div class='form-group col-md-6'>
          <button type='submit' class='btn btn-info rounded-0'>Keydi xogta </button>
        </div>

        ";
    }

}

if(isset($_POST['cname1'])){
    $cname = mysqli_real_escape_string($con, $_POST['cname1']);
    $phone  = mysqli_real_escape_string($con, $_POST['cphone1'] ) ;
    $email  = mysqli_real_escape_string($con, $_POST['cemail1'] ) ;
    $addr  = mysqli_real_escape_string($con, $_POST['addr1'] ) ;
    $status  = mysqli_real_escape_string($con, $_POST['status1'] ) ;
    $id = $_POST['custid'];

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    $sql = "SELECT * FROM customer WHERE customer_id = $id";
    $re = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($re);
    $curr_phone = $ro['cust_phone'];
    $curr_email = $ro['cust_email'];

    if($curr_phone != $phone){
        $stmt = "SELECT * FROM customer WHERE cust_phone = '$phone'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo "phone_found";
        }else{
            if($curr_email != $email){
                $stmt = "SELECT * FROM customer WHERE cust_email = '$email'";
                $result = mysqli_query($con, $stmt);
                if(mysqli_num_rows($result) > 0){
                    echo "found_email";
                }else{
                    $stmt = "UPDATE customer SET cust_name = '$cname', cust_phone = '$phone', cust_email = '$email', 
                    status = '$status', cust_addr = '$addr' WHERE customer_id = $id";
                    $result = mysqli_query($con, $stmt);
                    if($result){
                        echo 'success';
                    }
                }
            }else{
                $stmt = "UPDATE customer SET cust_name = '$cname', cust_phone = '$phone', cust_email = '$email', 
                status = '$status', cust_addr = '$addr' WHERE customer_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo 'success';
                }
            }
        }
    }else if($curr_email != $email){
        $stmt = "SELECT * FROM customer WHERE cust_email = '$email'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo "found_email";
        }else{
            $stmt = "UPDATE customer SET cust_name = '$cname', cust_phone = '$phone', cust_email = '$email', 
            status = '$status', cust_addr = '$addr' WHERE customer_id = $id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo 'success';
            } 
        }
    }else{
        $stmt = "UPDATE customer SET cust_name = '$cname', cust_phone = '$phone', cust_email = '$email', 
        status = '$status', cust_addr = '$addr' WHERE customer_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }
    }




}

?>
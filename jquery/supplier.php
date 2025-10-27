<?php 
include('../inc/session_config.php');
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['cname'])){
    $cname = mysqli_real_escape_string($con, $_POST['cname']);
    $phone  = mysqli_real_escape_string($con, $_POST['cphone'] ) ;
    $email  = mysqli_real_escape_string($con, $_POST['cemail'] ) ;
    $addr  = mysqli_real_escape_string($con, $_POST['addr'] ) ;
    $status  = mysqli_real_escape_string($con, $_POST['status'] ) ;
    $balance = isset($_POST['balance']) ? floatval($_POST['balance']) : 0;

    // Validate required fields
    if(empty($cname) || empty($phone) || empty($status)){
        echo "missing_fields";
        exit;
    }

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');
    $current_date = date('Y-m-d');


    $stmt = "SELECT * FROM supplier WHERE phone_num = '$phone' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        echo "phone_found";
    }else{

        if(empty($email)){
            $stmt = "SELECT COUNT(*) FROM supplier "; 
            $result = mysqli_query($con, $stmt); 
            $row = mysqli_fetch_array($result);
            $serial  = "SUP-" . 0000 . $row[0] + 1 ;

            $stmt = "INSERT INTO supplier(supp_ser, sup_name, phone_num, email_addr, address, status, reg_date, created_by, warehouse)";
            $stmt .= " VALUES ('$serial', '$cname', '$phone','$email', '$addr',  '$status', '$date', '$userid', 1)";
            $result = mysqli_query($con, $stmt); 
            if($result){
                $supplier_id = mysqli_insert_id($con);
                
                // If balance is provided, create a purchase record for opening balance
                if($balance > 0){
                    $purchase_serial = "PUR-OB-" . $supplier_id;
                    $purchase_stmt = "INSERT INTO purchase (supp_id, pur_status, pur_by, trans_date, warehouse, pur_date, ser, p_be_dis, gtotal, paid_amount, balance, payment_status) ";
                    $purchase_stmt .= "VALUES ($supplier_id, 'Recieved', $userid, '$date', 1, '$current_date', '$purchase_serial', $balance, $balance, 0, $balance, 'Not paid')";
                    mysqli_query($con, $purchase_stmt);
                }
                
                echo 'success';
            }else{
                echo 'error: ' . mysqli_error($con);
            }
        }else{
            $stmt = "SELECT * FROM supplier WHERE email_addr = '$email'";
            $result = mysqli_query($con, $stmt); 
            if(mysqli_num_rows($result) > 0){
                echo "found_email";
            }else{
               $stmt = "SELECT COUNT(*) FROM supplier "; 
               $result = mysqli_query($con, $stmt); 
               $row = mysqli_fetch_array($result);
               $serial  = "SUP-" . 0000 . $row[0] + 1 ;
    
               $stmt = "INSERT INTO supplier(supp_ser, sup_name, phone_num, email_addr, address, status, reg_date, created_by, warehouse)";
               $stmt .= " VALUES ('$serial', '$cname', '$phone','$email', '$addr',  '$status', '$date', '$userid', 1)";
               $result = mysqli_query($con, $stmt); 
               if($result){
                   $supplier_id = mysqli_insert_id($con);
                   
                   // If balance is provided, create a purchase record for opening balance
                   if($balance > 0){
                       $purchase_serial = "PUR-OB-" . $supplier_id;
                       $purchase_stmt = "INSERT INTO purchase (supp_id, pur_status, pur_by, trans_date, warehouse, pur_date, ser, p_be_dis, gtotal, paid_amount, balance, payment_status) ";
                       $purchase_stmt .= "VALUES ($supplier_id, 'Recieved', $userid, '$date', 1, '$current_date', '$purchase_serial', $balance, $balance, 0, $balance, 'Not paid')";
                       mysqli_query($con, $purchase_stmt);
                   }
                   
                   echo 'success';
               }else{
                   echo 'error: ' . mysqli_error($con);
               }
            }

        }
      
    }


}

//Delete customer
if(isset($_POST['del_supp'])){
    $id = $_POST['del_supp'];
    $stmt = "DELETE FROM supplier WHERE supp_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo "deleted";
    }
}


if(isset($_POST['getSuppliers'])){

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
      $stmt = "SELECT * FROM supplier ORDER by supp_id DESC";
      $result = mysqli_query($con, $stmt); 
      while($row = mysqli_fetch_assoc($result)){
          $i++;
          $cust_id = $row['supp_id'];
          $name = $row['sup_name'];
          $phone = $row['phone_num'];
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
                      <button type='button' class='btn btn-info btn-sm'  data-toggle='modal' data-target='#suppDetailModal' data-toggle='tooltip' data-placement='top' title='View Supplier detail' id='view_supp' data-id='$cust_id'> <i class='fa fa-eye'></i> </button>
                      <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editSuppModal' data-toggle='tooltip' data-placement='top' title='Edit Supplier detail' id='edit_supp' data-id='$cust_id'> <i class='fa fa-edit'></i> </button>
                      <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete Supplier' id='del_cust' data-id='$cust_id'> <i class='fa fa-trash'></i></button>
                      </div>


                  </td>
              </tr>
          ";
      }
}


if(isset($_POST['viewSingleSupp'])){
    $id = $_POST['viewSingleSupp'];

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
    $stmt = "SELECT supplier.*, users.fullname, warehouse.name from supplier, users, warehouse 
    WHERE supplier.created_by = users.userid and supplier.warehouse = warehouse.warehouseid AND supp_id = $id";
    $result = mysqli_query($con, $stmt); 

    while($row = mysqli_fetch_assoc($result)){
        $i++;
        $cust_id = $row['supp_id'];
        $name = $row['sup_name'];
        $phone = $row['phone_num'];
        $status = $row['status'];
        $date = date("M d, Y", strtotime($row['reg_date']));
        $username = $row['fullname'];
        $email = $row['email_addr'];
        $warehouse = $row['name'];
        $addr = $row['address'];

        $balance = get_balance($cust_id);

        if(empty($addr)){ $addr = "N/A"; }
        if(empty($email)){ $addr = "N/A"; }

        if($status == "Active"){
            $status = " <span class='badge badge-subtle badge-success'>$status</span> ";
        }else{
            $status = " <span class='badge badge-subtle badge-danger'>$status</span> ";
        }



        echo "
        <table class='table table-responsive table-bordered'>
        <thead>
            <tr>
                <th colspan='5' class='bg-info text-light'>Supplier details</th>
            </tr>
            <tr>
                <th>Magaca iibiyaha</th>
                <th>Taleefon</th>
                <th>Email</th>
                <th>Taarikh</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$name</td>
                <td>$phone</td>
                <td>$email</td>
                <td>$date</td>
                <td> <p>$status</p></td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan='2'>Qofka diiwan geliyay</th>
                <th colspan='2'>Goobta / Deegan</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan='2'>$username</td>
                <td colspan='2'>$addr</td>
                <td colspan='2'>$balance $</td>
            </tr>
        </tbody>
    </table>
        ";
    }
    
}

if(isset($_POST['editSingleSupp'])){
    $id = $_POST['editSingleSupp'];
    $stmt = "SELECT * FROM supplier WHERE supp_id = $id";
    $result = mysqli_query($con, $stmt); 
    while($row = mysqli_fetch_assoc($result)){
        $cust_id = $row['supp_id'];
        $name = $row['sup_name'];
        $phone = $row['phone_num'];
        $status = $row['status'];
        $date = date("M d, Y", strtotime($row['reg_date']));
        $email = $row['email_addr'];
        $addr = $row['address'];

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
          <label for=''> Magaca iibiyaha *</label>
          <input type='hidden' name='custid' id='custid' value='$cust_id'>
          <input type='text' name='cname1' id='cname1' value='$name' class='form-control rounded-0' required>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Taleefan *</label>
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
          <textarea name='addr1' id='addr1'  class='form-control rounded-0'>$addr</textarea>
        </div>

        <div class='form-group col-md-6'>
          <button type='submit' class='btn btn-info rounded-0'>Keydi xogta iibiyaha</button>
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

    $sql = "SELECT * FROM supplier WHERE supp_id = $id";
    $re = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($re);
    $curr_phone = $ro['phone_num'];
    $curr_email = $ro['email_addr'];

    if($curr_phone != $phone){
        $stmt = "SELECT * FROM supplier WHERE phone_num = '$phone'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo "phone_found";
        }else{
            if($curr_email != $email){
                $stmt = "SELECT * FROM supplier WHERE email_addr = '$email'";
                $result = mysqli_query($con, $stmt);
                if(mysqli_num_rows($result) > 0){
                    echo "found_email";
                }else{
                    $stmt = "UPDATE supplier SET sup_name = '$cname', phone_num = '$phone', email_addr = '$email', 
                    status = '$status', address = '$addr' WHERE supp_id = $id";
                    $result = mysqli_query($con, $stmt);
                    if($result){
                        echo 'success';
                    }
                }
            }else{
                $stmt = "UPDATE supplier SET sup_name = '$cname', phone_num = '$phone', email_addr = '$email', 
                status = '$status', address = '$addr' WHERE supp_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo 'success';
                }
            }
        }
    }else if($curr_email != $email){
        $stmt = "SELECT * FROM supplier WHERE email_addr = '$email'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo "found_email";
        }else{
            $stmt = "UPDATE supplier SET sup_name = '$cname', phone_num = '$phone', email_addr = '$email', 
            status = '$status', address = '$addr' WHERE supp_id = $id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo 'success';
            } 
        }
    }else{
        $stmt = "UPDATE supplier SET sup_name = '$cname', phone_num = '$phone', email_addr = '$email', 
        status = '$status', address = '$addr' WHERE supp_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }
    }




}

?>
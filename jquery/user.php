<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Note: Set Content-Type per branch to match response format

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['role'])){
    header('Content-Type: application/json');
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, md5( $_POST['password']));
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);

    // Validate input
    if(empty(trim($fname)) || empty(trim($email)) || empty(trim($password)) || empty(trim($phone))){
        echo json_encode(array('error' => 'empty_fields'));
        exit;
    }

    $stmt = "SELECT * FROM users WHERE email_addr = '$email'";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) > 0){
        echo json_encode(array('error' => 'found_email'));
    }else{
        $stmt = "SELECT * FROM users WHERE phone_number = '$phone'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_phone'));
        }else{
            $stmt = "INSERT INTO users (fullname, email_addr, usergroup, status, phone_number, warehouse, created_by, password)
            VALUES ('$fname', '$email', $role, '$status' ,'$phone', 1,  3, '$password')";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'insert_error', 'message' => mysqli_error($con)));
            }
        }
    }
   
}

if(isset($_POST['delete_user'])){
    header('Content-Type: application/json');
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['delete_user'];
    $stmt = "DELETE FROM users WHERE userid = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_error', 'message' => mysqli_error($con)));
    }
}

if(isset($_POST['edit_user'])){
    header('Content-Type: text/html; charset=UTF-8');
    $uid = (int)$_POST['edit_user'];
    $stmt = "SELECT * FROM users WHERE userid = $uid";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $fname = $row['fullname'];
        $email = $row['email_addr'];
        $phone = $row['phone_number'];
        $status  = $row['status'];
        $role  = $row['usergroup'];

        if($status == "Active"){
            $status = "
                <option value='Active'> Active</option>
                <option value='Disabled'> Disable</option>
            ";
        }else if ($status == "Disabled"){
            $status = "
            <option value='Disabled'> Disable</option>
            <option value='Active'> Active</option>
        ";
        }

        echo "
        <div class='form-group col-md-6'>
          <label for=''> Role *</label>
          <input type='hidden' name='user_id' id='user_id' value='$uid'>
          <select name='role1' id='role1' class='form-control' required> ";
           
              $stmt = "SELECT * FROM usergroup WHERE group_id = $role ";
              $result = mysqli_query($con, $stmt);
              while($row = mysqli_fetch_assoc($result)){
                $id = $row['group_id'];
                $name = $row['group_name'];
                echo "
                  <option value='$id'>$name</option>
                ";
              }

              $stmt = "SELECT * FROM usergroup WHERE group_id != $role ";
              $result = mysqli_query($con, $stmt);
              while($row = mysqli_fetch_assoc($result)){
                $id = $row['group_id'];
                $name = $row['group_name'];
                echo "
                  <option value='$id'>$name</option>
                ";
              }

            echo "
          </select>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Full name *</label>
          <input type='text' name='fname1' id='fname1' value='$fname' maxlength='50' class='form-control  rounded-0' placeholder='Enter fullname' autocomplete='off' required>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Email Address *</label>
          <input type='email' name='email1' id='email1' value='$email' maxlength='50' class='form-control  rounded-0' placeholder='Enter Emaill Address'  autocomplete='off' required>
        </div>

        <div class='form-group col-md-6'>
          <label for=''> Phone number *</label>
          <input type='text' name='phone1' id='phone1' value='$phone' maxlength='50' class='form-control  rounded-0' placeholder='Enter phone number'  autocomplete='off' required>
        </div>

        
        <div class='form-group col-md-6'>
          <label for=''> Password *</label>
          <input type='password' name='password1' id='password1' maxlength='50' class='form-control  rounded-0' placeholder='Enter password'  autocomplete='off' >
       
        </div>

        
        <div class='form-group col-md-6'>
          <label for=''> Status *</label>
          <select name='status1' id='status1' class='form-control'>
            $status
          </select>
        </div>



        <div class='form-group col-md-12 text-center'>
          <button type='submit' class='btn btn-success '>Update user</button>
        </div>
        ";
    }
}

if(isset($_POST['user_id'])){
    header('Content-Type: application/json');
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = (int)$_POST['user_id'];
    $fname = mysqli_real_escape_string($con, $_POST['fname1']);
    $role = mysqli_real_escape_string($con, $_POST['role1']);
    $email = mysqli_real_escape_string($con, $_POST['email1']);
    $password = mysqli_real_escape_string($con, $_POST['password1']);
    $status = mysqli_real_escape_string($con, $_POST['status1']);
    $phone = mysqli_real_escape_string($con, $_POST['phone1']);

    $sql = "SELECT * FROM users WHERE userid = $id";
    $res = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($res);
    $curr_phone = $ro['phone_number'];
    $curr_email = $ro['email_addr'];

    if($curr_phone == $phone){
        if($password == ""){
            $stmt = "UPDATE users SET fullname = '$fname', email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
            WHERE userid=$id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }
        }else{
            $stmt = "UPDATE users SET fullname = '$fname',password = '$password' ,email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
            WHERE userid=$id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }
        }
    }else if($curr_phone != $phone){
        $stmt = "SELECT * FROM users WHERE phone_number = '$phone'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'phone_found'));
        }else{
            if($password == ""){
                $stmt = "UPDATE users SET fullname = '$fname', email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
                WHERE userid=$id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo json_encode(array('success' => true));
                }
            }else{
                $stmt = "UPDATE users SET fullname = '$fname',password = '$password' ,email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
                WHERE userid=$id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo json_encode(array('success' => true));
                }
            }
        }
    }else if($email = $curr_email){
        if($password == ""){
            $stmt = "UPDATE users SET fullname = '$fname', email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
            WHERE userid=$id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }
        }else{
            $stmt = "UPDATE users SET fullname = '$fname',password = '$password' ,email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
            WHERE userid=$id";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }
        }
    }else if ($email != $curr_email){
        $stmt = "SELECT * FROM users WHERE email_addr = '$email'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo json_encode(array('error' => 'found_email'));
        }else{
            if($password == ""){
                $stmt = "UPDATE users SET fullname = '$fname', email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
                WHERE userid=$id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo json_encode(array('success' => true));
                }
            }else{
                $stmt = "UPDATE users SET fullname = '$fname',password = '$password' ,email_addr = '$email', usergroup = $role, phone_number = '$phone', status = '$status'
                WHERE userid=$id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo json_encode(array('success' => true));
                }
            }
        }
    }
}

?>

<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

// Check for session timeout before including config



include('../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['item'])){

    $item = mysqli_real_escape_string($con, $_POST['item']);
    $adj = mysqli_real_escape_string($con, $_POST['adjtype']);
    $qty = mysqli_real_escape_string($con, $_POST['qty']);
    $date = mysqli_real_escape_string($con, $_POST['date']);

    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $userid = $_SESSION['uid'];


    // Validate input
    if(empty(trim($item)) || empty(trim($qty)) || empty(trim($adj)) || empty(trim($date))){
        echo json_encode(array('error' => 'missing_fields'));
        exit;
    }

    // Validate quantity is numeric and positive
    if(!is_numeric($qty) || $qty <= 0){
        echo json_encode(array('error' => 'invalid_quantity'));
        exit;
    }

    $stmt = "INSERT INTO stock_adjustment (item_id , quantity, adju_type, date, created_by, warehouse)
    VALUES ($item, $qty, '$adj', '$date', $userid, 1)";
    $result = mysqli_query($con, $stmt);
    if($result){
        if($adj == "Add"){
            $stmt = "UPDATE item SET qty = qty + $qty WHERE item_id = $item ";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'update_failed', 'message' => mysqli_error($con)));
            }
        }else if($adj == "Subtract"){
            $stmt = "UPDATE item SET qty = qty - $qty WHERE item_id = $item ";
            $result = mysqli_query($con, $stmt);
            if($result){
                echo json_encode(array('success' => true));
            }else{
                echo json_encode(array('error' => 'update_failed', 'message' => mysqli_error($con)));
            }
        }else{
            echo json_encode(array('error' => 'invalid_adjustment_type'));
        }
    }else{
        echo json_encode(array('error' => 'insert_failed', 'message' => mysqli_error($con)));
    }

}

if(isset($_POST['curr_stock'])){
    $id = $_POST['curr_stock'];

    $stmt = "SELECT * FROM item WHERE item_id = $id";
    $result = mysqli_query($con, $stmt);
    if($row = mysqli_fetch_assoc($result)){
        echo $row['qty'];
    }
}

if(isset($_POST['del_adju'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['del_adju'];

    $stmt = "SELECT * FROM stock_adjustment WHERE sa_id = $id";
    $result = mysqli_query($con, $stmt); 
    $row = mysqli_fetch_assoc($result);
    $type = $row['adju_type'];
    $qty = $row['quantity'];
    $iid = $row['item_id'];

    $s = "SELECT qty FROM item WHERE item_id = $iid";
    $res = mysqli_query($con, $s);
    $curr_qty = mysqli_fetch_array($res); 

    if($type == "Add"){

        if($curr_qty >= $qty){
            $sql = "UPDATE item SET qty = qty - $qty WHERE item_id = $iid"; 
            $re = mysqli_query($con, $sql); 
            if($re){
                $stmt = "DELETE FROM stock_adjustment WHERE sa_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo "deleted";
                }
            }
        }else{
            $sql = "UPDATE item SET qty = 0 WHERE item_id = $iid"; 
            $re = mysqli_query($con, $sql); 
            if($re){
                $stmt = "DELETE FROM stock_adjustment WHERE sa_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo "deleted";
                }
            }
        }

    }else if($type == "Subtract"){
        if($curr_qty >= $qty){
            $sql = "UPDATE item SET qty = qty + $qty WHERE item_id = $iid"; 
            $re = mysqli_query($con, $sql); 
            if($re){
                $stmt = "DELETE FROM stock_adjustment WHERE sa_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo "deleted";
                }
            }
        }else{
            $sql = "UPDATE item SET qty = 0 WHERE item_id = $iid"; 
            $re = mysqli_query($con, $sql); 
            if($re){
                $stmt = "DELETE FROM stock_adjustment WHERE sa_id = $id";
                $result = mysqli_query($con, $stmt);
                if($result){
                    echo "deleted";
                }
            }
        }
    }
}


if(isset($_POST['editSingleAdj'])){
    $id = $_POST['editSingleAdj'];

    $stmt = "SELECT * FROM stock_adjustment WHERE sa_id = $id ";
    $result = mysqli_query($con, $stmt); 
    if($row = mysqli_fetch_assoc($result)){
        $adj = $row['adju_type'];
        $qty = $row['quantity'];
        $iid = $row['item_id'];
        $date = date('Y-m-d', strtotime($row['date']));

        echo "
        <div class='form-group col-md-12'>
        <label for=''>Item *</label>
        <input type='hidden' name='sa_id' id='sa_id' value='$id' >
        <select name='item1' id='bss3' class='form-control customer' required data-toggle='selectpicker' required data-live-search='true' >";
            $stmt = "SELECT * FROM item WHERE item_id IN (SELECT item_id from stock_adjustment WHERE item_id = $iid) ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_assoc($result)){
              $id = $row['item_id'];
              $name = $row['item_name'];
              echo "
                <option data-tokens='$id' value='$id'>$name  </option>
              ";
            }
            $stmt = "SELECT * FROM item WHERE item_id NOT IN (SELECT item_id from stock_adjustment WHERE item_id = $iid) ";
            $result = mysqli_query($con, $stmt);
            while($row = mysqli_fetch_assoc($result)){
              $id = $row['item_id'];
              $name = $row['item_name'];
              echo "
                <option data-tokens='$id' value='$id'>$name  </option>
              ";
            }
          
        echo "
        </select>
      </div>
      <div class='col-md-6'>
        <label for=''>Current stock</label>
        <p id='curr_stock'>-</p>
      </div>
      <div class='col-md-6'>
        <label for=''>Quantity *</label>
        <input type='number' min='1' name='qty1' id='qty1' value='$qty' required class='form-control'>
      </div>

      <div class='form-group col-md-12'>
        <label for=''>Adjustment type *</label>
        <select name='adjtype1' id='adjtype1' class='form-control' required> ";

        if($adj == "Add"){
            echo "
            <option value='Add'>Add</option>
            <option value='Subtract'>Subtract</option>
            ";
        }else if($adj == "Subtract"){
            echo "
            <option value='Add'>Add</option>
            <option value='Subtract'>Subtract</option>
            ";
        }
            
        
    echo "
        </select>
      </div>

      <div class='form-group col-md-12'>
        <label for=''>Date *</label>
        <input type='date' name='date1' value='$date' id='date1' class='form-control' required>
      </div>

        ";

    }
}

if(isset($_POST['sa_id'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['sa_id'];

    $item = mysqli_real_escape_string($con, $_POST['sa_id']);
    $adj = mysqli_real_escape_string($con, $_POST['adjtype1']);
    $qty = mysqli_real_escape_string($con, $_POST['qty1']);
    $date = mysqli_real_escape_string($con, $_POST['date1']);

    $sql = "SELECT * FROM stock_adjustment WHERE sa_id = $id";
    $res  = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($result);
    $curr_item = $ro['item_id'];
    $curr_qty = $ro['quantity'];
    $curr_adj = $ro['adju_type'];

    if($item != $curr_item){

        
    }


}
?>
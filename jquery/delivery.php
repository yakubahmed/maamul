<?php 
include('../path.php');

// Check for session timeout before including config
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired - return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Session expired', 'redirect' => true));
    exit();
}

session_start();

include('../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['invoicenum'])){
    //$dmeth = mysqli_real_escape_string($con, $_POST['dmeth']);
    $invoicenum = mysqli_real_escape_string($con, 'INV-' . $_POST['invoicenum']);
    //$ddate = mysqli_real_escape_string($con, $_POST['ddate']);

    $stmt = "SELECT * FROM orders WHERE ser = '$invoicenum'";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) < 1){
        echo "not-found";
    }else{
        $row = mysqli_fetch_assoc($result);
        $cid = $row['cust_id'];
        $i=0;
        $stmt = "SELECT order_item.*, item.item_name, item.unit FROM order_item, item WHERE order_item.item_id = item.item_id AND order_item.order_id IN (SELECT order_id from orders WHERE ser = '$invoicenum');";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $pname = $row['item_name'];
            $price = $row['price'];
            $qty = $row['qty'];
            $id = $row['item_id'];
            $unit_id = $row['unit'];
            $i++;
            echo "
                <tr>
                    <input type='hidden' name='item_id[]' value='$id'>
                    <input type='hidden' name='unit_id[]' value='$unit_id'>
                    <input type='hidden' name='cust_id' id='cust_id' value='$cid'>
                    <td>$i</td>
                    <td>$pname</td>
                    <td> <input type='text' name='qty[]' value='$qty' id='qty$i'  value='0' disabled class='form-control' step='0.01'> </td>
                    <td> <input type='text' name='delivered[]' id='delivered$i' data-id='$i' value='0' class='form-control delivered'> </td>
                    <td> <input type='text' name='balance[]' id='balance$i' value='$qty' disabled class='form-control'> </td>
                </tr>
            ";
        }
    }

}

//Saving 

if(isset($_POST['invoice'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        // Debug: Log session state
        error_log("Session check failed - uid: " . (isset($_SESSION['uid']) ? $_SESSION['uid'] : 'not set') . 
                 ", isLogedIn: " . (isset($_SESSION['isLogedIn']) ? $_SESSION['isLogedIn'] : 'not set'));
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }

    $dmeth = mysqli_real_escape_string($con, $_POST['dmeth']);
    $invoicenum = mysqli_real_escape_string($con, 'INV-' . $_POST['invoice']);
    $ddate = mysqli_real_escape_string($con, $_POST['ddate']);
    $id = $_SESSION['uid'];
    $date = date('Y-m-d');
    $custid = $_POST['custid'];


    // Validate required fields
    if(empty(trim($invoicenum)) || empty(trim($ddate)) || empty(trim($custid))){
        echo json_encode(array('error' => 'missing_fields'));
        exit;
    }

    $stmt = "INSERT INTO del_note (invoice_number, del_method, despatch_date, delivery_by, warehouse, tran_date, cust_id, status ) 
    VALUES ('$invoicenum', $dmeth, '$ddate',$id, 1, '$date', $custid, 'on-going' )";
    $result = mysqli_query($con, $stmt);
    if($result){
        $last_id = mysqli_insert_id($con);
        
        $all_items_success = true;
        foreach ($_POST["delid"] AS $key => $item){               
            $stm ="INSERT INTO del_note_item(item_id, qty, delivered, balance,unit_id ,date ,del_note_id)
             VALUES ( " . $_POST["iid"][$key] . ", " . $_POST["qty"][$key] . ", ". $_POST["delid"][$key]  . ", " . $_POST["balid"][$key]. ", " . $_POST["unit_id"][$key]. ",'$date',  $last_id  )";
           $item_result = mysqli_query($con, $stm);
           if(!$item_result){
               $all_items_success = false;
               break;
           }
        }
        
        if($all_items_success){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('error' => 'item_insert_failed', 'message' => mysqli_error($con)));
        }
    }else{
        echo json_encode(array('error' => 'insert_failed', 'message' => mysqli_error($con)));
    }

}

if(isset($_POST['viewSingleDel'])){
    $id = $_POST['viewSingleDel'];
    function total_items_unit($id){
        global $con;
        $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[1];
      }

      function del_items_unit($id){
        global $con;
        $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[1];
      }
      function balance_unit($id){
        global $con;
        $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[1];
      }


    function total_items($id){
        global $con;
        $stmt = "SELECT SUM(qty) FROM del_note_item WHERE del_note_id  = $id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[0];
    }
    function del_items($id){
        global $con;
        $stmt = "SELECT SUM(delivered) FROM del_note_item WHERE del_note_id  = $id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[0];
    }
    function balance($id){
        global $con;
        $stmt = "SELECT SUM(balance) FROM del_note_item WHERE del_note_id  = $id";
        $result = mysqli_query($con, $stmt);
        $row = mysqli_fetch_array($result);
        return $row[0];
    }
  

    $stmt = "SELECT del_note.del_note_id, del_note.invoice_number,del_note.despatch_date,users.fullname ,del_note.tran_date, delivery_method.meth_name, customer.cust_name, customer.cust_phone FROM del_note, delivery_method,users ,customer 
    WHERE del_note.cust_id = customer.customer_id AND del_note.del_method = delivery_method.del_meth_id AND users.userid = del_note.delivery_by AND del_note.del_note_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
        $invoice =  $row['invoice_number'];
        $did = 'DEL-' . $row['del_note_id'];
        $date = date('d-m-Y', strtotime($row['tran_date']));
        $date = date('d-m-Y', strtotime($row['despatch_date']));
        $customer = $row['cust_name'];
        $userby = $row['fullname'];
        $total_unit = total_items_unit($row['del_note_id']);
        $del_items_unit = del_items_unit($row['del_note_id']);
        $balance_unit = balance_unit($row['del_note_id']);
        $total =  total_items($row['del_note_id']);
        $del =  del_items($row['del_note_id']);
        $balance =  balance($row['del_note_id']);

        echo "
        <div class='col-md-12'> <hr> </div>

            <div class='form-group col-md-4'>
                <label for=''> <strong>Inoive No</strong> </label>
                <p>$invoice</p>
                
            </div>
            <div class='form-group col-md-4'>
                <label for=''> <strong>Del No</strong> </label>
                <p>$did</p>
                
            </div>
            <div class='form-group col-md-4'>
                <label for=''> <strong>Customer name</strong> </label>
                <p>$customer</p>
            </div>

            <div class='col-md-12'> <hr> </div>

            <div class='form-group col-md-4'>
                <label for=''> <strong>Total items</strong> </label>
                <p>$total $total_unit</p>
            </div>

            <div class='form-group col-md-4'>
                <label for=''> <strong>Delivered items</strong> </label>
                <p>$del $del_items_unit</p>
            </div>

            <div class='form-group col-md-4'>
                <label for=''> <strong>Outstanding</strong> </label>
                <p>$balance  $balance_unit</p>
            </div>

            <div class='col-md-12'> <hr> </div>

            <div class='form-group col-md-4 '>
                <label for=''> <strong>Delivered by</strong> </label>
                <p>$userby</p>
             </div>

             <div class='col-md-12'>
             <ul class='nav nav-tabs' id='myTab' role='tablist'>
                 <li class='nav-item' role='presentation'>
                 <a class='nav-link active' id='home-tab' data-toggle='tab' href='#home' role='tab' aria-controls='home' aria-selected='true'>ITEMS</a>
                 </li>
                
             </ul> 
             <div class='tab-content' id='myTabContent'>
             <div class='tab-pane fade show active' id='home' role='tabpanel' aria-labelledby='home-tab'>
             <table class='table table-table-striped'>
                 <thead>
                 <tr>
                     <th>#</th>
                     <th>Item name</th>
                     <th>Ordered</th>
                     <th>Delivered</th>
                     <th>Outstanding</th>
                 </tr>
                 </thead>
                 <tbody>
        ";

        $i=0;
        $stmt = "SELECT item.item_name, del_note_item.qty, delivered, balance, date FROM item, del_note_item WHERE item.item_id = del_note_item.item_id AND del_note_item.del_note_id = $id";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $i++;
            $iname = $row['item_name'];
            $qty = $row['qty'];
            $del = $row['delivered'];
            $bal = $row['balance'];

            echo "
                <tr>
                    <td>$i</td>
                    <td>$iname</td>
                    <td>$qty</td>
                    <td>$del</td>
                    <td>$bal</td>
                </tr>
            ";
        }

        
    }

    
}

if(isset($_POST['btnDelSale'])){
    // Check if user is logged in
    if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
        echo json_encode(array('error' => 'not_logged_in'));
        exit;
    }
    
    $id = $_POST['btnDelSale'];

    $stmt = "DELETE FROM del_note WHERE del_note_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('error' => 'delete_failed', 'message' => mysqli_error($con)));
    }
}
?>
<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['invoice'])){
    $dmeth = mysqli_real_escape_string($con, $_POST['dmeth']);
    $invoicenum = mysqli_real_escape_string($con, 'INV-' . $_POST['invoice']);
    $ddate = mysqli_real_escape_string($con, $_POST['ddate']);
    $id = $_SESSION['uid'];
    $date = date('Y-m-d');
    $did = $_POST['did'];




    $stmt = "UPDATE del_note SET invoice_number = '$invoicenum', del_method=$dmeth, despatch_date = '$ddate' 
   WHERE del_note_id  = $did";
    $result = mysqli_query($con, $stmt);
    if($result){
        $last_id = mysqli_insert_id($con);
        $stmt = "DELETE FROM del_note_item WHERE del_note_id = $did";
        $result = mysqli_query($con, $stmt);
        if($result){
            foreach ($_POST["delid"] AS $key => $item){               
                $stm ="INSERT INTO del_note_item(item_id, qty, delivered, balance, date, del_note_id)
                 VALUES ( " . $_POST["iid"][$key] . ", " . $_POST["qty"][$key] . ", ". $_POST["delid"][$key]  . ", " . $_POST["balid"][$key]. ", '$date', '$did'  )";
               $result = mysqli_query($con, $stm);
               
            }   
        }
        
        echo 'success';

    }

}


?>
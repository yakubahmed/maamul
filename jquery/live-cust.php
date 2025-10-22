<?php 
// Start output buffering to prevent any extra output
ob_start();

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

// Check if get_customers request
if(isset($_POST['get_customers'])) {
    $stmt = "SELECT * FROM customer WHERE status = 'Active' ORDER BY cust_name ASC";
    $result = mysqli_query($con, $stmt);
    $data = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            "customer_id" => $row["customer_id"], 
            "cust_name" => $row["cust_name"],
            "cust_phone" => $row["cust_phone"],
            "cust_email" => $row["cust_email"]
        );
    }

    // Clean output and return JSON
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["data" => $data]);
    exit;
}

// Default behavior - Customer Live
$stmt = "SELECT * FROM customer ORDER BY customer_id DESC";
$result = mysqli_query($con, $stmt);
while($row = mysqli_fetch_assoc($result)) {
    $data[] = array("id" => $row["customer_id"], "name" => $row["cust_name"]);
}

echo json_encode(["data" => $data]);

?>


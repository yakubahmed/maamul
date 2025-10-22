<?php
include('../inc/session_config.php');
session_start();

include('../inc/config.php');

// Check if user is logged in
if (!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Not logged in'));
    exit();
}

// Check if this is a session extension request
if (isset($_POST['extend_session']) && $_POST['extend_session'] === 'true') {
    // Update the session timestamp
    $_SESSION['last_activity'] = time();
    
    // Update database with last activity
    if (isset($_SESSION['uid'])) {
        $user_id = $_SESSION['uid'];
        $current_time = date('Y-m-d H:i:s');
        
        $stmt = "UPDATE users SET last_activity = '$current_time' WHERE userid = $user_id";
        mysqli_query($con, $stmt);
    }
    
    // Return success response
    echo 'success';
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}
?>

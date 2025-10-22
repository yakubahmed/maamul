<?php

$host = 'localhost';
$database = 'maamul-v2';
$user = 'root'; 
$password = '';

$con  = mysqli_connect($host, $user, $password, $database); 

if(!$con){
    echo "Connection Failed";
}

// Check for session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired
    session_unset();
    session_destroy();
    
    // If this is an AJAX request, return JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(array('error' => 'Session expired'));
        exit();
    }
    
    // Redirect to login page
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

?>
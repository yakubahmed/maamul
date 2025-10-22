<?php 

include('path.php'); 


session_start();
session_destroy();
ob_clean();

header('location: ' . BASE_URL . 'login')


?>
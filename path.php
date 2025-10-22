<?php


// This file reprents for 
// CONSTRAINT of BASE_URL, ROOT_PATH
// 
$url = isset($_SERVER['SERVER_NAME']) ? 'http://' . $_SERVER['SERVER_NAME'] : 'http://localhost';
define("ROOT_PATH", dirname(__FILE__)); 
define("BASE_URL", 'http://localhost/ramaas/'); 

// echo ROOT_PATH;
// // echo BASE_URL;

?>
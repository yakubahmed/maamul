<?php
include('path.php');
include('inc/session_config.php');
session_start();

$allowed = ['so','en'];
$lang = isset($_GET['lang']) ? strtolower($_GET['lang']) : 'so';
if (!in_array($lang, $allowed)) { $lang = 'so'; }
$_SESSION['lang'] = $lang;

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : BASE_URL;
header('Location: ' . $redirect);
exit;
?>


<?php 
ob_start();  
include( ROOT_PATH . '/inc/session_config.php' );
session_start(); 
include( ROOT_PATH . '/inc/config.php' ); 
include( ROOT_PATH . '/inc/lang.php' );
?>
<?php if(!isset($_SESSION['isLogedIn'])){ header('location: login' ); } ?>
<?php include( ROOT_PATH . '/inc/access_control.php' ); ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'so') ?>">
<?php $isactive = "" ?>
<?php $isactive2 = "" ?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> <?= __t($title ?? '') ?> | Maamul Software </title>
    <meta property="og:title" content="Dashboard">
    <meta name="author" content="Yakub Ahmed">
    <meta property="og:locale" content="en_US">
    <meta name="description" content="">
    <meta property="og:description" content="">
    <link rel="canonical" href="#">
    <meta property="og:url" content="#">
    <meta property="og:site_name" content="Dashboard - Maamul Software">

    <!-- FAVICONS -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?= BASE_URL ?>assets/images/logo/icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/logo/icon.png">
    <meta name="theme-color" content="#3063A0"><!-- End FAVICONS -->
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet"><!-- End GOOGLE FONT -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/open-iconic/font/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/flatpickr/flatpickr.min.css"><!-- END PLUGINS STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/theme.min.css" data-skin="default">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/theme-dark.min.css" data-skin="dark">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/custom.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/bootstrap-select/css/bootstrap-select.min.css">
    <!-- Data tables -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css">

    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/toastr/toastr.min.css">
    
    <!-- iziToaster -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/toastr/toastr.min.css">
    <script>
      var skin = localStorage.getItem('skin') || 'default';
      var disabledSkinStylesheet = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
      // Disable unused skin immediately
      disabledSkinStylesheet.setAttribute('rel', '');
      disabledSkinStylesheet.setAttribute('disabled', true);
      // add loading class to html immediately
      document.querySelector('html').classList.add('loading');
    </script><!-- END THEME STYLES -->
    
    <!-- Session Timeout Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/session-timeout.css">
  </head>

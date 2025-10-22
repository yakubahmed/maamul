<?php include('../path.php'); ?>

<style>
  body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    .invoice {
      width: 210mm;
      height: 297mm;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #000;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .footer {
      text-align: center;
      position: absolute;
      bottom: 20px;
      width: 100%;
    }

</style>

<?php $title = "Add customer"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body >
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->
      <?php include(ROOT_PATH . '/inc/sidebar.php'); ?>
      <!-- .app-main -->
      <main class="app-main">
        <!-- .wrapper -->
        <div class="wrapper">
          <!-- .page -->
          <div class="page">
            <!-- .page-inner -->
            <div class="page-inner">
              <!-- .page-title-bar -->
              
            </div><!-- /.page-inner -->
          </div><!-- /.page -->
        </div><!-- .app-footer -->
          <?php include(ROOT_PATH .'/inc/footer.php'); ?>
        <!-- /.app-footer -->
        <!-- /.wrapper -->
      </main><!-- /.app-main -->
    </div><!-- /.app -->
<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>


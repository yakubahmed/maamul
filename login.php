<?php session_start(); ?>
<?php include('path.php'); ?>

<!DOCTYPE html>
<html lang="en">
  
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> Sign In | Maamul Software </title>
    <meta property="og:title" content="Sign In">
    <meta name="author" content="Yakub Ahmed">
    <meta property="og:locale" content="en_US">
    <meta name="description" content="">
    <meta property="og:description" content="">
    <link rel="canonical" href="#">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/logo/icon.png">
    <link rel="shortcut icon" href="assets/images/logo/icon.png">
    <meta name="theme-color" content="#3063A0"><!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet"><!-- End Google font -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css"><!-- END PLUGINS STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="assets/stylesheets/theme.min.css" data-skin="default">
    <link rel="stylesheet" href="assets/stylesheets/theme-dark.min.css" data-skin="dark">
    <link rel="stylesheet" href="assets/stylesheets/custom.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/toastr/toastr.min.css">
    <script>
      var skin = localStorage.getItem('skin') || 'default';
      var disabledSkinStylesheet = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
      // Disable unused skin immediately
      disabledSkinStylesheet.setAttribute('rel', '');
      disabledSkinStylesheet.setAttribute('disabled', true);
      // add loading class to html immediately
      document.querySelector('html').classList.add('loading');
    </script><!-- END THEME STYLES -->

    <style>
    /* Blurry Login Container */
    .auth-form {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin: 1rem;
    }
    
    .auth-form.blurbg {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
    }
    
    /* Enhanced blur for better visibility */
    .auth-form .form-control {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .auth-form .form-control:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(52, 108, 176, 0.5);
        box-shadow: 0 0 0 3px rgba(52, 108, 176, 0.1);
    }
    
    .auth-form .btn {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    </style>
    
  </head>
  <body>
    <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
    <![endif]-->
    <!-- .auth -->
    <main class="auth" style='' >
      <header id="auth-header" class="auth-header" style='padding:30px;'>
        <img src="assets/images/logo/logo.png" height='90' alt="" class='' >
        <span style='font-weight:bold; font-size:20px;'></span>
      </header><!-- form -->
      <?php //if(isset($_SESSION['auth'])): ?>
      <form class='auth-form d-none blurbg' method="post" id='frmAuth' >
        <div class="overlay d-none" id='loading-auth'>
            <i class="fas fa-2x fa-sync fa-spin text-light"></i>
        </div>

        <center>
        <img src="assets/images/logo/ramaas_logo.png"  class='py-2' height='90' alt="" style=''>
         
        </center>

      

        <div class="form-group mb-4 d-none" id='errMsgAuth'>
          <div class="alert alert-danger alert-dismissible fade show msgContent1" role="alert">
            Invalid Username or Password
          </div>
        </div>
        <h6 class='text-center'> Enter 6 digits that we sent to your email. <small><a href="">Resend</a></small>  </h6>
        <div class="row">
         
          <div class="form-group col-md-12">
            <input type="text" name="auth-code" id="auth-code" class="form-control" required placeholder='######'>
          </div>

          <div class="form-group col-md-12 my-4">
            <button type="submit" class="btn btn-info btn-block">
              Continue
            </button>
          </div>
        </div>
      </form>
      <?php // endif; ?>
      <form class="auth-form blurbg my-2" method='post' id='frmLogin' >
        <div class="overlay d-none" id='loading-login'>
            <i class="fas fa-2x fa-sync fa-spin text-light"></i>
        </div>
     
        <center>
          <img src="assets/images/logo/ramaas_logo.png" class='' height='80' alt="" >
        </center>
        <div class="form-group mb-4 d-none" id='errMsg'>
          <div class="alert alert-danger alert-dismissible fade show msgContent" role="alert">
            Invalid Username or Password
          </div>
        </div>


        <!-- .form-group -->
        <div class="form-group my-3">
          <div class="form-label-group">
            <input type="text" id="username" name='username' required class="form-control" placeholder="Fadlan geli cinwaanka" autofocus="" autocomplete="off"> <label for="username">Cinwaan</label>
          </div>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group">
          <div class="form-label-group">
            <input type="password" id="password" name='password' required class="form-control" placeholder="Fadlan geli furre sireed kaaga"> <label for="password">Furre sireed</label>
          </div>
        </div><!-- /.form-group -->
        <!-- .form-group -->
        <div class="form-group">
          <button class="btn btn-lg btn-primary btn-block" id='btnLogin' type="submit">Gal</button>
        </div><!-- /.form-group -->

        <!-- recovery links -->
        <div class="text-center pt-3">
          <a href="#" class="link" id='fpass' data-toggle="modal" data-target="#contactModal">Password ayaa ilaaway?</a>
        </div><!-- /recovery links -->
      </form><!-- /.auth-form -->
      <?php ?>
      <!-- copyright -->
      <footer class="auth-footer"> © 2022 - <?= date('Y') ?> All Rights Reserved. Developed by <a href="https://xidigtech.com" target='_blank'><strong>XIDIG TECH</strong></a>
      </footer>
    </main><!-- /.auth -->

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
          <div class="modal-header" style="background: #346cb0; color: white; border-radius: 10px 10px 0 0; border: none; padding: 1rem;">
            <h5 class="modal-title" id="contactModalLabel">
              <i class="fas fa-phone mr-2"></i>Contact
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 1.5rem; text-align: center;">
            <p class="mb-3">Contact developer for password recovery:</p>
            <div style="font-size: 1.2rem; font-weight: bold; color: #346cb0;">
              <i class="fas fa-phone mr-2"></i>+252 61 624 6740
          </div>
          <div class="modal-footer" style="border: none; padding: 1rem; text-align: center;">
            <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 5px;">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- BEGIN BASE JS -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper.js/umd/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script> <!-- END BASE JS -->
    <!-- BEGIN PLUGINS JS -->
    <script src="assets/vendor/particles.js/particles.js"></script>
        <!-- SweetAlert2 -->
        <script src="assets/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="assets/toastr/toastr.min.js"></script>
    <script>
      /**
       * Keep in mind that your scripts may not always be executed after the theme is completely ready,
       * you might need to observe the `theme:load` event to make sure your scripts are executed after the theme is ready.
       */
      $(document).on('theme:init', () =>
      {
        /* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
        particlesJS.load('auth-header', 'assets/javascript/pages/particles.json');
      })
    </script> <!-- END PLUGINS JS -->
    <!-- BEGIN THEME JS -->
    <script src="assets/javascript/theme.js"></script> <!-- END THEME JS -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-116692175-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-116692175-1');
    </script>
  </body>

</html>



<script>
  $(document).ready(function(){

    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });


    $("#frmLogin").on('submit', function(e){
      e.preventDefault();
      $('#loading-login').removeClass('d-none')
      //$('#btnLogin').attr('disabled', true)
      var count = 0;
      
           $.ajax({
               url: 'jquery/login.php', 
               type:'post', 
               data: $('#frmLogin').serialize(),
               success: function(res){
                   // Trim whitespace from response
                   res = res.trim();
                   console.log('Login response:', res);
                   
                   if(res == 'success'){
                    location.href = "<?= BASE_URL ?>"
                   }else if (res == 'failedToLogin'){

                      $('#errMsg').removeClass('d-none')
                      $('.msgContent').html("Xogta aad gelisay ma ahan mid sax ah.")
                      $('#loading-login').addClass('d-none')
                      //$('#btnLogin').attr('disabled', false)

                      setTimeout(() => {
                        $('#errMsg').addClass('d-none')
                      }, 3500);
                      
                      
                      toastr.error("Xogta aad gelisay ma ahan mid sax ah")
                   } else if(res == 1){
                     $('#code-1').focus();
                    $('#loading-login').removeClass('d-none')
                     $("#frmLogin").addClass("d-none")
                     $("#frmAuth").removeClass("d-none")
                     

                   } else if(res == "disabled"){
                     $('#errMsg').removeClass('d-none')
                      $('.msgContent').html("SORRY. you can not login into your account, it's disabled.")
                      $('#loading-login').addClass('d-none')
                      //$('#btnLogin').attr('disabled', false)

                      setTimeout(() => {
                        $('#errMsg').addClass('d-none')
                      }, 5500);
                      
                      toastr.error("SORRY. you can not login into your account, it's disabled.")
                      
                   } else if(res == 'database_error'){
                      $('#errMsg').removeClass('d-none')
                      $('.msgContent').html("Database connection error. Please try again.")
                      $('#loading-login').addClass('d-none')
                      
                      setTimeout(() => {
                        $('#errMsg').addClass('d-none')
                      }, 3500);
                      
                      toastr.error("Database connection error. Please try again.")
                   } else {
                      console.log('Unexpected response:', res);
                      toastr.error("Cilad ayaa jirta fadlan mar kle isku day.")
                     
                      $('#errMsg').removeClass('d-none')
                      $('.msgContent').html("Cilad ayaa jirta fadlan mar kle isku day.")
                      $('#loading-login').addClass('d-none')
                     // $('#btnLogin').attr('disabled', false)

                      setTimeout(() => {
                        $('#errMsg').addClass('d-none')
                        
                      }, 3500);
                   }

               },
               error: function(xhr, status, error) {
                   console.log('AJAX Error:', status, error);
                   console.log('Response:', xhr.responseText);
                   
                   $('#errMsg').removeClass('d-none')
                   $('.msgContent').html("Network error. Please check your connection and try again.")
                   $('#loading-login').addClass('d-none')
                   
                   setTimeout(() => {
                       $('#errMsg').addClass('d-none')
                   }, 3500);
                   
                   toastr.error("Network error. Please check your connection and try again.")
               }
           })
    })



    $('#frmAuth').on('submit', function(e){
      e.preventDefault();
      $('#loading-auth').removeClass('d-none')
      $.ajax({
          url: 'jquery/login.php', 
          type:'post', 
          data: $('#frmAuth').serialize(),
          success: function(res){
            // Trim whitespace from response
            res = res.trim();
            console.log('Auth response:', res);
            
            if(res == "success"){
              location.href = "<?= BASE_URL ?>"
            }else if(res == "error_code"){
               $('#loading-auth').addClass('d-none')

              $('#errMsgAuth').removeClass('d-none')
              $('.msgContent1').html("Invalid verification code")
              setTimeout(() => {
                $('#errMsgAuth').addClass('d-none')
              }, 3500);
              
              toastr.error("Invalid verification code")
            } else {
              $('#loading-auth').addClass('d-none')
              toastr.error("An error occurred. Please try again.")
            }
          },
          error: function(xhr, status, error) {
            console.log('AJAX Error:', status, error);
            $('#loading-auth').addClass('d-none')
            toastr.error("Network error. Please try again.")
          }
      });
    });

    // Auto-format auth code input
    $('#auth-code').on('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
      if (this.value.length === 6) {
        $('#frmAuth').submit();
      }
    });

  });
</script>
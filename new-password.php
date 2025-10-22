<?php session_start(); ?>
<?php include('inc/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
  
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title> Reset Password | EasyPos </title>
    <meta property="og:title" content="Reset password">
    <meta name="author" content="Yakub Ahmed">
    <meta property="og:locale" content="en_US">
    <meta name="description" content="">
    <meta property="og:description" content="">
    <link rel="canonical" href="#">
    <meta property="og:url" content="#">
    <meta property="og:site_name" content="EasyPos">
    <script type="application/ld+json">
      {
        "name": "EasPos Software",
        "description": "",
        "author":
        {
          "@type": "Person",
          "name": "Yakub Ahmed"
        },
        "@type": "WebSite",
        "url": "",
        "headline": "Sign In",
        "@context": "http://schema.org"
      }
    </script><!-- End SEO tag -->
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/logo/logo_icon_blue.png">
    <link rel="shortcut icon" href="assets/images/logo/logo_icon_blue.png">
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
      .Short {  
    width: 100%;  
    background-color: #dc3545;  
    margin-top: 5px;  
    height: 3px;  
    color: #dc3545;  
    font-weight: 500;  
    font-size: 12px;  
}  
.Weak {  
    width: 100%;  
    background-color: #ffc107;  
    margin-top: 5px;  
    height: 3px;  
    color: #ffc107;  
    font-weight: 500;  
    font-size: 12px;  
}  
.Good {  
    width: 100%;  
    background-color: #28a745;  
    margin-top: 5px;  
    height: 3px;  
    color: #28a745;  
    font-weight: 500;  
    font-size: 12px;  
}  
.Strong {  
    width: 100%;  
    background-color: #d39e00;  
    margin-top: 5px;  
    height: 3px;  
    color: #d39e00;  
    font-weight: 500;  
    font-size: 12px;  
}  
    </style>
  </head>
  <body>
    <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
    <![endif]-->
    <!-- .auth -->
    <main class="auth">
      <header id="auth-header" class="auth-header" style=" ">
        <img src="assets/images/logo/easposwhite.png" height='50' alt="" >
        <span style='font-weight:bold; font-size:20px;'>For logistics</span>
      </header><!-- form -->


<?php 
if(!isset($_GET['ref']) || empty($_GET['ref'])){
    header('location: https://app.submalco.so/404' ); 
}else{
    $code = $_GET['ref'];
    $stmt = "SELECT * FROM users WHERE resetPasswordCode = '$code'";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) < 1){
         header('location: https://app.submalco.so/404' ); 
    }
}
?>

  
      <form class="auth-form " method='post' id='frmResetPassword' style='background: rgba(255,255,255,0.5);
-webkit-backdrop-filter: blur(10px);
backdrop-filter: blur(10px);
border: 1px solid rgba(255,255,255,0.25);'>
        <div class="overlay d-none" id='loading-login'>
            <i class="fas fa-2x fa-sync fa-spin text-light"></i>
        </div>
                

        <center>
          <img src="assets/images/logo/ramaas_logo.jpg"  height='100' alt="" style='padding: 5% 4%;'>
        </center>
        <div class="form-group mb-4 d-none" id='errMsg'>
          <div class="alert alert-success alert-dismissible fade show msgContent" role="alert">
            Invalid Username or Password
          </div>
        </div>


        <!-- .form-group -->
        <div class="form-group">
          <div class="form-label-group">
            <input type="hidden" name="code" id='code' value='<?= $_GET['ref'] ?>'>
            <input type="password" id="password" name='password' required class="form-control" placeholder="New password" autofocus="" autocomplete="off"> <label for="username">New Password</label>
            <div id="strengthMessage"></div>  
          </div>
          
        </div><!-- /.form-group -->

        <div class="form-group">
        <div class="form-label-group">
            <input type="password" id="cpassword" name='cpassword' required class="form-control" placeholder="Confirm password" autofocus="" autocomplete="off"> <label for="username">Confirm password</label>
          </div>
        </div>

        <!-- .form-group -->
        <div class="form-group">
          <button class="btn btn-lg btn-primary btn-block" id='btnLogin' type="submit">Submit</button>
        </div><!-- /.form-group -->

     
      </form><!-- /.auth-form -->
      <?php ?>
      <!-- copyright -->
      <footer class="auth-footer"> © 2022 All Rights Reserved. Developed by <a href="https://smartplusadvert.so"><strong> SmartPlus ICT</strong></a>
      </footer>
    </main><!-- /.auth -->
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
        timer: 4000
    });


    $("#frmResetPassword").on('submit', function(e){
      e.preventDefault();
      $('#loading-login').removeClass('d-none')
      //$('#btnLogin').attr('disabled', true)
      var count = 0;

      var password = $('#password').val()
      var cpassword = $('#cpassword').val();
      if(password == cpassword){
        $.ajax({
               url: 'jquery/new-password.php', 
               type:'post', 
               data: $('#frmResetPassword').serialize(),
               success: function(res){
                   if(res == 'success'){
                    $('#loading-login').addClass('d-none')
                    $('#errMsg').removeClass('d-none')
                    $('.msgContent').html("Password updated successfully.")
                    toastr.success('Password updated successfully')
                    setTimeout(() => {
                        location.href = 'https://app.submalco.so/login'
                     
                    }, 500);
                   }else{
                     
                      toastr.error("Something is wrong please try again.")
                     
                      $('#errMsg').removeClass('d-none')
                      $('.msgContent').html("Something is wrong please try again.")
                      $('#loading-login').addClass('d-none')
                     // $('#btnLogin').attr('disabled', false)

                      setTimeout(() => {
                        $('#errMsg').addClass('d-none')
                        
                      }, 3500);
                   }

               }
           })
      }else{
        toastr.error("Both password does'nt match.")
      
        $('#loading-login').addClass('d-none')
      }
      

    })


    $('#frmAuth').on('submit', function(e){
      e.preventDefault();
      $('#loading-auth').removeClass('d-none')
      $.ajax({
          url: 'http://localhost/ganacsi-kaal/jquery/login.php', 
          type:'post', 
          data: $('#frmAuth').serialize(),
          success: function(res){
            if(res == "success"){
              location.href = 'http://localhost/ganacsi-kaal/'
            }else if(res == "error_code"){
               $('#loading-auth').addClass('d-none')

              $('#errMsgAuth').removeClass('d-none')
              $('.msgContent1').html("The code number that you have entered is incorrect.")
              toastr.error("Invalid code")
              setTimeout(() => {
                        $('#errMsgAuth').addClass('d-none')
                        
              }, 3500);
            }else{
              $('#loading-auth').addClass('d-none')

              toastr.error("SOmething is wrong please try again")
              console.log(res)
            }
          }
        })
    });
 
    
  })

  $(document).ready(function () {  
    $('#password').keyup(function () {  
        $('#strengthMessage').html(checkStrength($('#password').val()))  
    })  
    function checkStrength(password) {  
        var strength = 0  
        if (password.length < 6) {  
            $('#strengthMessage').removeClass()  
            $('#strengthMessage').addClass('Short')  
            return 'Too short'  
        }  
        if (password.length > 7) strength += 1  
        // If password contains both lower and uppercase characters, increase strength value.  
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1  
        // If it has numbers and characters, increase strength value.  
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1  
        // If it has one special character, increase strength value.  
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1  
        // If it has two special characters, increase strength value.  
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1  
        // Calculated strength value, we can return messages  
        // If value is less than 2  
        if (strength < 2) {  
            $('#strengthMessage').removeClass()  
            $('#strengthMessage').addClass('Weak')  
            return 'Weak'  
        } else if (strength == 2) {  
            $('#strengthMessage').removeClass()  
            $('#strengthMessage').addClass('Good')  
            return 'Good'  
        } else {  
            $('#strengthMessage').removeClass()  
            $('#strengthMessage').addClass('Strong')  
            return 'Strong'  
        }  
    }  
});  
</script>
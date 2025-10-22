<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add customer"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->

      <?php $menu = 'Customers'  ?>
      
      <?php $smenu = 'Add customer'  ?>
      
      
      
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
              <header class="page-title-bar">
              <div class="row">
                <div class="col-md-6">
                  <h1 class="page-title"> Diiwan gelin <small>Macaamiil</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>customer/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> Liiska Macaamiisha </a>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">
                      <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
                      
                      <div class="card-body ">
                        
                        <form class='row' method='post' id='frmCust'>

                          <div class='form-group col-md-4'>
                            <label for=''> Magaca macaamilka *</label>
                            <input type='text' name='cname' id='cname' class='form-control rounded-0' placeholder='' autocomplete='off' required>
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Taleefon *</label>
                            <input type='text' name='cphone' id='cphone' class='form-control  rounded-0' placeholder='' autocomplete='off' required>
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Email Address</label>
                            <input type='text' name='cemail' id='cemail' class='form-control rounded-0' placeholder='' autocomplete='off'>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Haraa / Lagu leeyahay</label>
                            <input type='text' name='balance' id='balance' class='form-control rounded-0' placeholder='' autocomplete='off'>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Status *</label>
                            <select name='status' id='status' class='form-control' >
                              <option value=''> Select status</option>
                              <option value='Active'> Active</option>
                              <option value='Disabled'> Disabled</option>
                            </select>
                          </div>

                          <div class='form-group col-md-12'>
                            <label for=''> Goobta uu degan yahay</label>
                            <textarea name='addr' id='addr' class='form-control rounded-0' placeholder=''></textarea>
                          </div>

                          <div class='form-group col-md-12'>
                            <center>
                              <button type='submit' class='btn btn-info rounded-0'>Keydi xogta macaamiilka</button>
                            </center>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->
             

              </div><!-- /.page-section -->
            </div><!-- /.page-inner -->
          </div><!-- /.page -->
        </div><!-- .app-footer -->
          <?php include(ROOT_PATH .'/inc/footer.php'); ?>
        <!-- /.app-footer -->
        <!-- /.wrapper -->
      </main><!-- /.app-main -->
    </div><!-- /.app -->
<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

  $('#frmCust').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/customer.php', 
      type:'post', 
      data: $('#frmCust').serialize(),
      success: function(res){
        if(res == 'found_email'){
          toastr.error("Iimeylka aad gelisay qof ayaa horay loogu diiwan geliyay, fadlan mid cusub geli.")
          $('.overlay').addClass('d-none')
          $('#cemail').addClass('is-invalid')
        }else if (res == "phone_found"){
          $('.overlay').addClass('d-none')
          toastr.error("Taleefon ka aad gelisay qof ayaa horay loogu diiwan geliyay, fadlan mid cusub geli")
          $('#cphone').addClass('is-invalid')
        }else if (res == 'success'){
          toastr.success("Waala keydiyay xogta macaamiilka")
          setTimeout(() => {
            window.location.replace("<?= BASE_URL ?>customer/list")
            
          }, 1000);
          $('#frmCust')[0].reset();
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')
        }else{
          toastr.error("Cilad ayaa jirta, fadlan mar kle isku day.")
          console.log(res);
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')
        }
      }
    })
  })



})

</script>
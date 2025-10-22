<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add Account"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Koonto'  ?>
      
      <?php $smenu = 'Sameey koonto'  ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->
      <?php $isactive = "Accounts" ?>
      <?php $isactive2 = "Add account" ?>
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
                  <h1 class="page-title"> Account registration </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>account/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Accounts </a>
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
                        
                        <form class='row' method='post' id='frmAddAcc'>

                          <div class='form-group col-md-6'>
                            <label for=''> Account name *</label>
                            <input type='text' name='accname' id='accname' maxlength='50' class='form-control rounded-0' autocomplete='off' required>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Account number *</label>
                            <input type='number' name='accnum' id='accnum' maxlength='30' class='form-control  rounded-0'  autocomplete='off' required>
                          </div>


                          <div class='form-group col-md-12'>
                            <label for=''> Description</label>
                            <textarea name='accdes' id='accdes' class='form-control rounded-0'></textarea>
                          </div>

                          <div class='form-group col-md-6'>
                            <button type='submit' class='btn btn-info rounded-0'>Save account</button>
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

  $('#frmAddAcc').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/account.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmAddAcc').serialize(),
      success: function(res){
        if(res.error === 'found_accnum'){
          toastr.error("This acccount number is registered with another account. Try another one.")
          $('.overlay').addClass('d-none')
          $('#accnum').addClass('is-invalid')
        }else if (res.error === 'empty_fields'){
          toastr.error("Please fill in all required fields")
          $('.overlay').addClass('d-none')
        }else if (res.error === 'not_logged_in'){
          toastr.error("Session expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        }else if (res.success === true){
          toastr.success("Account created successfully")
          window.location.replace("<?= BASE_URL ?>account/list")
          $('#frmAddAcc')[0].reset();
          $('.overlay').addClass('d-none')
          $('#accnum').removeClass('is-invalid')
        }else{
          toastr.error("Something went wrong. Please try again.")
          $('.overlay').addClass('d-none')
          console.log(res)
        }
      },
      error: function(xhr, status, error){
        console.log('AJAX Error:', error);
        console.log('Response:', xhr.responseText);
        toastr.error("Network error. Please try again.");
        $('.overlay').addClass('d-none');
      }
    })
  })



})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add User"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Users'  ?>
      
      <?php $smenu = 'Add user'  ?>
  <body>
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
              <header class="page-title-bar">
              <div class="row">
                <div class="col-md-6">
                  <h1 class="page-title"> User registration </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>user/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Users </a>
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
                        
                        <form class='row' method='post' id='frmAddUser'>

                          <div class='form-group col-md-6'>
                            <label for=''> Role *</label>
                            <select name="role" id="role" class="form-control" required>
                              <option value="">Select role</option>
                              <?php 
                                $stmt = "SELECT * FROM usergroup";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  $id = $row['group_id'];
                                  $name = $row['group_name'];
                                  echo "
                                    <option value='$id'>$name</option>
                                  ";
                                }
                              ?>
                            </select>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Full name *</label>
                            <input type='text' name='fname' id='fname' maxlength='50' class='form-control  rounded-0' placeholder="Enter fullname" autocomplete='off' required>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Email Address *</label>
                            <input type='email' name='email' id='email' maxlength='50' class='form-control  rounded-0' placeholder='Enter Emaill Address'  autocomplete='off' required>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Phone number *</label>
                            <input type='text' name='phone' id='phone' maxlength='50' class='form-control  rounded-0' placeholder='Enter phone number'  autocomplete='off' required>
                          </div>

                          
                          <div class='form-group col-md-6'>
                            <label for=''> Password *</label>
                            <input type='password' name='password' id='password' maxlength='50' class='form-control  rounded-0' placeholder='Enter password'  autocomplete='off' required>
                          </div>

                          
                          <div class='form-group col-md-6'>
                            <label for=''> Status *</label>
                            <select name="status" id="status" class="form-control">
                              <option value="">Select status</option>
                              <option value="Active">Active</option>
                              <option value="Disabled">Disable</option>
                            </select>
                          </div>



                          <div class='form-group col-md-12 text-center'>
                            <button type='submit' class='btn btn-info rounded-0'>Save user</button>
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

  $('#frmAddUser').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    
    $.ajax({
      url:'../jquery/user.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmAddUser').serialize(),
      success: function(res){
        if(res.error === 'found_email'){
          toastr.error("The email you have entered is registered with another user.")
          $('.overlay').addClass('d-none')
          $('#email').addClass('is-invalid')
        }else if(res.error === "found_phone"){
          toastr.error("The phone number you have entered is registered with another user.")
          $('.overlay').addClass('d-none')
          $('#phone').addClass('is-invalid')
        }else if(res.error === 'empty_fields'){
          toastr.error("Please fill in all required fields")
          $('.overlay').addClass('d-none')
        }else if(res.error === 'not_logged_in'){
          toastr.error("Session expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        }else if (res.success === true){
          toastr.success("User created successfully")
          $('#frmAddUser')[0].reset();
          $('.overlay').addClass('d-none')
          setTimeout(() => {
            window.location.replace("<?= BASE_URL ?>user/list")
          }, 500);
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

  $(document).on('keyup', '#email', function(){
    $('#email').removeClass('is-invalid')
  })

  $(document).on('keyup', '#phone', function(){
    $('#phone').removeClass('is-invalid')
  })


})

</script>
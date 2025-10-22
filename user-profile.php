<?php include('path.php'); ?>
<?php $menu = 'user-profile'  ?>
      
      <?php $smenu = ''  ?>
      
<style>
 
</style>

<?php $title = 'Stock Adjustment';  include(ROOT_PATH . '/inc/header.php'); ?>

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
          <header class="page-cover">
              <div class="text-center">
                <a  class="user-avatar user-avatar-xl"><img src="<?= BASE_URL ?>assets/images/<?=get_curr_profile() ?>" alt=""></a>
                <h2 class="h4 mt-2 mb-0"> <?= $_SESSION['fname'] ?> </h2>
                
                <p class="text-muted"> <?= get_role() ?></p>
              
              </div><!-- .cover-controls -->
              
            </header><!-- /.page-cover -->
            <!-- .page-inner -->
            <div class="page-inner">

              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->

                <div class="row">
                    <div class="col-lg-4">
                        <!-- .card -->
                        <div class="card card-fluid">
                          <h6 class="card-header"> Settings </h6><!-- .nav -->
                          <nav class="nav nav-tabs flex-column border-0">
                            <a href="#" id='btn-profile' class="nav-link active">Profile</a> 
                            <a href="#" id='btn-security' class="nav-link">Security</a>
                          </nav><!-- /.nav -->
                        </div><!-- /.card -->
                    </div>

                    <div class="col-lg-8" id='BasicInfo'>
                    <!-- .card -->
                    <div class="card card-fluid">
                      <h6 class="card-header"> Basic Information </h6><!-- .card-body -->
                      <div class="card-body">
                        <form  method="post" id='frmBasicInfo' enctype='multipart/form-data'>
                                                  <!-- .media -->
                        <div class="media mb-3">
                          <!-- avatar -->
                          <div class="user-avatar user-avatar-xl fileinput-button">
                            <div class="fileinput-button-label"> Change photo </div><img id='img_desp' src="<?= BASE_URL ?>assets/images/<?=get_curr_profile() ?>" alt="" > <input id="image"  type="file" name="image" onchange="previewFile(this);">
                          </div><!-- /avatar -->
                          <!-- .media-body -->
                          <div class="media-body pl-3">
                            <h3 class="card-title"> Profile image </h3>
                            <h6 class="card-subtitle text-muted"> Click the current avatar to change your photo. </h6>
                            <p class="card-text">
                              <small>JPG, GIF or PNG 400x400, &lt; 2 MB.</small>
                            </p><!-- The avatar upload progress bar -->
                            <div id="progress-avatar" class="progress progress-xs fade">
                              <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div><!-- /avatar upload progress bar -->
                          </div><!-- /.media-body -->
                        </div><!-- /.media -->
                    
                          <hr>
                          <!-- form row -->
                          <div class="form-row">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">Full name</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="text" class="form-control" id="input02" name='fname' id='fname' value="<?= $_SESSION['fname'] ?>" >
                            </div><!-- /form column -->
                          </div><!-- /form row -->
                          <!-- form row -->
                          <div class="form-row">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">Email Address</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="text" class="form-control" id="input02" value="<?= $_SESSION['uname'] ?>" readonly>
                            </div><!-- /form column -->
                          </div><!-- /form row -->
                          

                          <div class="form-row">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">Phone number</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="text" class="form-control" id="input02" value="<?=  get_phone() ?>" readonly>
                            </div><!-- /form column -->
                          </div><!-- /form row -->
                          
                         
                          <hr>
                          <!-- .form-actions -->
                          <div class="form-actions">
                            <button type="submit" class="btn btn-primary ml-auto">Update Info</button>
                          </div><!-- /.form-actions -->
                        </form><!-- /form -->
                      </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <!-- .card -->

                  </div>
                    <div class="col-lg-8 d-none" id='Security'>
                    <!-- .card -->
                    <div class="card card-fluid">
                      <h6 class="card-header"> Security </h6><!-- .card-body -->
                      <div class="card-body">
                        
                        <!-- form -->
                        <form method="post" id='frmPassword' class=''>
                          <!-- form row -->
                          <h5>Change password</h5>
                          <hr>
                          <!-- form row -->
                          <div class="form-row">
                            <input type="hidden" name="security" id='security' value="true">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">Current Password</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="password" class="form-control" id="input02" name='cpassword' value="" >
                            </div><!-- /form column -->
                          </div><!-- /form row -->
                          <!-- form row -->
                          <div class="form-row">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">New password</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="password" class="form-control" id="input02" name='npassword' value="" >
                               <div id="strengthMessage"></div> 
                            </div><!-- /form column -->
                          </div><!-- /form row -->
                          <!-- form row -->
                          <div class="form-row">
                            <!-- form column -->
                            <label for="input02" class="col-md-3 text-right">Confirm password</label> <!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-9 mb-3">
                              <input type="password" class="form-control" id="input02" name='conpass' value="" >
                            </div><!-- /form column -->
                          </div><!-- /form row -->


                          <h3>2 Step verification</h3>
                          <div class="form-row  ">
                              <select name="2step" id="2step" class="form-control">
                                <?php 
                                  $id = $_SESSION['uid'];
                                  $stmt = "SELECT * FROM users WHERE userid = $id";
                                  $result = mysqli_query($con, $stmt);
                                  $row = mysqli_fetch_assoc($result);
                                  $auth = $row['auth'];
                                  if($auth == "false"){
                                    echo "
                                        <option value='true'>ON</option>
                                      <option value='false'>OFF</option>
                                      
                                    ";
                                  }else{
                                    echo "
                                    <option value='false'>OFF</option>
                                    <option value='true'>ON</option>
                                    
                                  ";
                                  }
                                ?>
                               >
                              </select>
                          </div>
                         
                          

                          
                         
                          <hr>
                          <!-- .form-actions -->
                          <div class="form-actions">
                            <button type="submit" class="btn btn-primary ml-auto">Save changes</button>
                          </div><!-- /.form-actions -->
                        </form><!-- /form -->
                      </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <!-- .card -->

                  </div>

                </div>

                  


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

<?php 

function get_curr_profile(){
  global $con; 
  $id = $_SESSION['uid'];
  $stmt = "SELECT * FROM users WHERE userid = $id";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_assoc($result)){
    $image = $row['profile'];

    if(empty($image)){
      $image = "profile.png";
    }

    return $image;
  }
}

function get_phone(){
  global $con; 
  $id = $_SESSION['uid'];
  $stmt = "SELECT * FROM users WHERE userid = $id";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_assoc($result)){
    $phone_number = $row['phone_number'];

    

    return $phone_number;
  }
}




?>


<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

  $('#image').change(function(){
        const file = this.files[0];
        console.log(file);
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('#img_desp').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });


      //Delete product
      $(document).on('click', '#del_adju', function(){
        var id = $(this).data('id');
        console.log(id)
        console.log(id)
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url:'../jquery/stock-adjustment.php',
                  type:'post',
                  data: 'del_adju='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Stock adjustment deleted successfully.',
                        'success'
                      )
                      location.reload();
                    }else{
                      Swal.fire(
                        'Error!',
                        'Something is wrong please try again.',
                        'error'
                      )
                      
                    }
                  }
                })

              }
            })
      })


      function get_customers(){
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: 'getCustomers=24',
            success: function(data){
                $('tbody').html(data)
            }
        });
      }

      // Displaying customer detail
      $(document).on('click', '#view_cust', function(){
        var id = $(this).data('id')
        $('.overlay').removeClass('d-none');
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: 'viewSingleCust='+id,
            success: function(data){
                $('#custDetailDisp').html(data)
                $('.overlay').addClass('d-none');

            }
        });
      })

      // Edit customer - displaying information
      $(document).on('click', '#edit_adju', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/stock-adjustment.php',
            type:'post',
            data: 'editSingleAdj='+id,
            success: function(data){
                $('#frmEditAdj').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })

      //Updating Customer Detail
      $('#frmBasicInfo').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'jquery/user-setting.php', 
          type:'post', 
          cache: false,
          contentType: false, // you can also use multipart/form-data replace of false
          processData: false,
          data: new FormData(this),
          success: function(res){
            if (res == 'success'){
              toastr.success("Your information is updated successfully")
              $('#frmBasicInfo')[0].reset();
             
              setTimeout(() => {
                window.location.replace("https://app.submalco.so/logout")

                
              }, 500);

            }else{
              toastr.error("Something is wrong please try again")
              console.log(res)
            }
          }
        })
      });



      $(document).on('change', '#bss3', function(){
        var id = $(this).val()
        $.ajax({
            url:'../jquery/stock-adjustment.php',
            type:'post',
            data: 'curr_stock='+id,
            success: function(data){
                $('#curr_stock').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })


      $('#frmPassword').on('submit', function(e){
        e.preventDefault();
        console.log($('#step').val())
        $.ajax({
          
          url:'jquery/user-setting.php', 
          type:'post', 
          data: $('#frmPassword').serialize(),
          success: function(res){
            if (res == 'success'){
              toastr.success("Security updated successfully")
              $('#frmPassword')[0].reset();
             
              setTimeout(() => {
                window.location.replace("https://app.submalco.so/logout")
                
              }, 500);

            }else if (res == "invalid-password"){
              toastr.error("Invalid Current password")
              $('#frmPassword')[0].reset();

            }else if (res == "mismatch"){
              toastr.error("New password and confirm password does'nt match")
            }else{
              toastr.error("Something is wrong please try again")
              console.log(res)
            }
          }
        })
      });


      $(document).on('click', '#btn-profile',function(){
        $('#BasicInfo').removeClass('d-none')
        $('#Security').addClass('d-none')

        $('#btn-security').removeClass('active')
        $('#btn-profile').addClass('active')
      })

      $(document ).on('click','#btn-security' ,function(){
        $('#BasicInfo').addClass('d-none')
        $('#Security').removeClass('d-none')

        $('#btn-security').addClass('active')
        $('#btn-profile').removeClass('active')
      })

})

 $(document).ready(function () {  
    $('#npassword').keyup(function () {  
        $('#strengthMessage').html(checkStrength($('#npassword').val()))  
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
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'User list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Users'  ?>
      
      <?php $smenu = 'User list'  ?>
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
                  <h1 class="page-title"> User list </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>user/add" class="btn btn-info text-right"> <i class="fa fa-user"></i> Add User </a>
                    <a href="<?= BASE_URL ?>user/groups" class="btn btn-success text-right ml-2"> <i class="fa fa-users"></i> User Groups </a>
                    <a href="<?= BASE_URL ?>user/permissions" class="btn btn-warning text-right ml-2"> <i class="fa fa-key"></i> Permissions </a>
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
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Full name</th>
                              <th>Phone</th>
                              <th>Email</th>
                              <th>Role</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                              <?php 
                                $i = 0;
                                $id = $_SESSION['uid'];

                                $stmt = "SELECT users.*, usergroup.group_name FROM users, usergroup 
                                WHERE users.usergroup = usergroup.group_id AND users.userid != $id ORDER by userid DESC";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['userid'];
                                    $name = $row['fullname'];
                                    $email = $row['email_addr'];
                                    $phone = $row['phone_number'];
                                    $role = $row['group_name'];
                                    $status = $row['status'];

                                    if($status == "Active"){
                                        $status = "<span class='badge badge-success'>$status</span>";
                                    }else{
                                        $status = "<span class='badge badge-danger'>$status</span>";

                                    }

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$name</td>
                                            <td>$phone</td>
                                            <td>$email</td>
                                            <td>$role</td>
                                            <td>$status</td>
                                            <td>
                                            <div class='dropdown d-inline-block'>
                                                <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                <div class='dropdown-menu dropdown-menu-right' style=''>
                                                <div class='dropdown-arrow'></div>
                                                <button type='button' data-toggle='modal' data-target='#editUserModal' id='btnEditUser' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit user</button> 
                                                <button type='button' id='delete_user' data-id='$id' class='dropdown-item'><i class='fa fa-trash'></i> Delete</button>
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                    ";
                                }
                         
                              
                              ?>
                                
                               
                          </tbody>

                        </table>
              
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->
             
                <!-- Customer detail modal drawer -->
                <div class="modal modal-drawer fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>User Details</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" id='custDetailDisp'>
                                <form class='userDetails row' method='post' id='frmEditUser'>

                                </form>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

              </div><!-- /.page-section -->

         

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



      //Delete product
      $(document).on('click', '#delete_user', function(){
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
                  url:'../jquery/user.php',
                  type:'post',
                  dataType: 'json',
                  data: 'delete_user='+id,
                  success: function(data){
                    if(data.success === true){
                      Swal.fire(
                        'Deleted!',
                        'User deleted successfully.',
                        'success'
                      )
                      location.reload()
                    }else if(data.error === 'not_logged_in'){
                      Swal.fire(
                        'Session Expired!',
                        'Please login again.',
                        'error'
                      ).then(() => {
                        window.location.href = '<?= BASE_URL ?>login.php';
                      });
                    }else{
                      Swal.fire(
                        'Error!',
                        'Something is wrong please try again.',
                        'error'
                      )
                      
                    }
                  },
                  error: function(xhr, status, error){
                    console.log('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                    Swal.fire(
                      'Network Error!',
                      'Please try again.',
                      'error'
                    );
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
      $(document).on('click', '#edit_cust', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: 'editSingleCust='+id,
            success: function(data){
                $('.editCustPlace').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })

      //Updating Customer Detail
      $('#frmEditUser').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/user.php', 
          type:'post', 
          data: $('#frmEditUser').serialize(),
          success: function(res){
            if(res == 'found_email'){
              toastr.error("This email account is allready with another customer, try another one")
              $('#email1').addClass('is-invalid')
            }else if (res == "phone_found"){
              toastr.error("This phone number is allready registered with another customer, try another one ")
              $('#phone1').addClass('is-invalid')
            }else if (res == 'success'){
              toastr.success("User Updated successfully")
              $('#frmEditUser')[0].reset();
              $('#editCustModal').modal('hide');
              $('#cphone1').removeClass('is-invalid')
              $('#cemail1').removeClass('is-invalid')
              location.reload();

            }else{
                console.log(res)
            }
          }
        })
      });

    $(document).on('click', '#btnEditUser', function(){
        var id = $(this).data('id')

        $.ajax({
          url:'../jquery/user.php', 
          type:'post', 
          data: {edit_user:id},
          success: function(res){ 
            $('.userDetails').html(res)
          } 
        })
    })




})

</script>
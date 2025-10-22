<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Customer list';  include(ROOT_PATH . '/inc/header.php'); ?>

      <?php $menu = 'Customers'  ?>
      
      <?php $smenu = 'Customer list'  ?>
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
                  <h1 class="page-title"> Liiska Macaamiisha </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>customer/add" class="btn btn-info text-right"> <i class="fa fa-user-plus"></i> Macaamiil cusub </a>
                </div>
                <a href="<?= BASE_URL ?>customer/add" class="btn btn-success btn-floated "><span class="fa fa-plus my-2"></span></a>
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
                              <th>Magaca</th>
                              <th>Taleefon</th>
                              <th>Haraa / Lagu leeyahay</th>
                              <th>Status</th>
                              <th>Taarikh</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              function get_balance($id){
                                global $con; 
                                $stmt = "SELECT SUM(balance) FROM orders WHERE cust_id = $id ";
                                $result = mysqli_query($con, $stmt);
                                $row = mysqli_fetch_array($result);
                                if(empty($row[0])){
                                  return "0.00";
                                }else{
                                  return $row[0];
                                }
                              }
                            ?>
                              <?php 
                                $i = 0;
                                $stmt = "SELECT * FROM customer ORDER by customer_id DESC";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $cust_id = $row['customer_id'];
                                    $name = $row['cust_name'];
                                    $phone = $row['cust_phone'];
                                    $status = $row['status'];
                                    $date = date("M d, Y", strtotime($row['reg_date']));
                                    if($status == "Active"){
                                      $status = " <span class='badge badge-subtle badge-success'>$status</span> ";
                                    }else{
                                        $status = " <span class='badge badge-subtle badge-danger'>$status</span> ";
                                    }
                                    $balance = get_balance($cust_id);

                                    if($phone == ""){ $phone = "N/A"; }

                                    if($cust_id == 29){ $balance = "N/A"; } else{ $balance = $balance . " $"; }

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$name</td>
                                            <td>$phone</td>
                                            <td>$balance </td>
                                            <td> <p>$status</p> </td>
                                            <td>$date</td>
                                            <td>";
                                            if($cust_id != 29){
                                              echo "
                                                <div class='dropdown d-inline-block'>
                                                  <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                  <div class='dropdown-menu dropdown-menu-right' style=''>
                                                    <div class='dropdown-arrow'></div>
                                                    <button type='button' data-toggle='modal' data-target='#custDetailModal' id='view_cust' data-id='$cust_id' class='dropdown-item'> <i class='fa fa-eye'></i> View Customer</button> 
                                                    <button type='button' data-toggle='modal' data-target='#editCustModal' id='edit_cust' data-id='$cust_id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Customer</button>
                                                    <button type='button' class='dropdown-item btn-danger' id='del_cust' data-id='$cust_id'><i class='fa fa-trash'></i> Delete Customer</button>
                                                  </div>
                                                </div>
                                              "; }echo "
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
                <div class="modal modal-drawer fade" id="custDetailModal" tabindex="-1" role="dialog" aria-labelledby="custDetailModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Customer <small>Detail</small> </strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" id='custDetailDisp'>
                           

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn bg-red text-light"  data-dismiss="modal"> Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

              </div><!-- /.page-section -->

              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="editCustModal" tabindex="-1" role="dialog" aria-labelledby="editCustModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Xogta macaamiilka <small class='text-primary'>Wax ka badalid</small></strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='editCustPlace row' method='post' id='frmEditCust' >
                                
                                </form>
                              </div>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

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
      $(document).on('click', '#del_cust', function(){
        var id = $(this).data('id');
        console.log(id)
        console.log(id)
            Swal.fire({
              title: 'Ma hubtaa?',
              text: "Hadii aad tirto xogtan dib uma heli doontid",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Haa, iska tir!',
              cancelButtonText: 'Maya, waan ka laabtay'
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url:'../jquery/customer.php',
                  type:'post',
                  data: 'del_cust='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Customer deleted successfully.',
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
      $(document).on('click', '#edit_cust', function(){
        var id = $(this).data('id')
        console.log('Edit customer clicked, ID:', id);
        
        if(!id){
          toastr.error('Customer ID not found');
          return;
        }
        
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: {editSingleCust: id},
            beforeSend: function(){
              $('.editCustPlace').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
            },
            success: function(data){
                console.log('Customer data loaded');
                $('.editCustPlace').html(data)
            },
            error: function(xhr, status, error){
                console.log('Error loading customer:', error);
                toastr.error('Failed to load customer data');
                $('.editCustPlace').html('<div class="alert alert-danger">Failed to load customer data. Please try again.</div>');
            }
        });
      })

      //Updating Customer Detail
      $(document).on('submit', '#frmEditCust', function(e){
        e.preventDefault();
        
        console.log('Form submitted');
        
        $.ajax({
          url:'../jquery/customer.php', 
          type:'post', 
          data: $('#frmEditCust').serialize(),
          success: function(res){
            res = res.trim();
            console.log('Response:', res);
            
            if(res == 'found_email'){
              toastr.error("This email account is already with another customer, try another one")
              $('#cemail1').addClass('is-invalid')
            }else if (res == "phone_found"){
              toastr.error("This phone number is already registered with another customer, try another one")
              $('#cphone1').addClass('is-invalid')
            }else if (res == 'success'){
              toastr.success("Customer information updated successfully")
              setTimeout(() => {
                location.reload();
              }, 1000);
            }else{
              toastr.error("Error updating customer: " + res)
              console.log('Error:', res);
            }
          },
          error: function(xhr, status, error){
            console.log('AJAX Error:', error);
            toastr.error("Network error. Please try again.");
          }
        })
      });




})

</script>
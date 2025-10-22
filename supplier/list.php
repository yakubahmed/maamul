<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Supplier list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'People'  ?>
      
      <?php $smenu = 'Iibiye'  ?>
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
                  <h1 class="page-title"> Liiska iibiye yaasha </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>supplier/add" class="btn btn-info text-right"> <i class="fa fa-user-plus"></i> Iibiye Cusub </a>
                    <a href="<?= BASE_URL ?>supplier/add" type="button" class="btn btn-success btn-floated"><span class="fa fa-plus py-2"></span></a>
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
                              <th>Magaca iibiye</th>
                              <th>Taleefon</th>
                              <th>Haraa / igu leeyahay</th>
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
                                $stmt = "SELECT * FROM supplier ORDER by supp_id DESC";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $cust_id = $row['supp_id'];
                                    $name = $row['sup_name'];
                                    $phone = $row['phone_num'];
                                    $status = $row['status'];
                                    $date = date("M d, Y", strtotime($row['reg_date']));
                                    if($status == "Active"){
                                      $status = " <span class='badge badge-subtle badge-success'>$status</span> ";
                                    }else{
                                        $status = " <span class='badge badge-subtle badge-danger'>$status</span> ";
                                    }
                                    $balance = get_balance($cust_id);

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$name</td>
                                            <td>$phone</td>
                                            <td>$balance $</td>
                                            <td> <p>$status</p> </td>
                                            <td>$date</td>
                                            <td>
                                                <div class='dropdown d-inline-block'>
                                                  <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                  <div class='dropdown-menu dropdown-menu-right' style=''>
                                                    <div class='dropdown-arrow'></div>
                                                    <button type='button' data-toggle='modal' data-target='#suppDetailModal' id='view_supp' data-id='$cust_id' class='dropdown-item'> <i class='fa fa-eye'></i> View Supplier</button> 
                                                    <button type='button' data-toggle='modal' data-target='#editSuppModal' id='edit_supp' data-id='$cust_id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Supplier</button>
                                                    <button type='button' class='dropdown-item btn-danger' id='del_cust' data-id='$cust_id'><i class='fa fa-trash'></i> Delete Supplier</button>
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
                <div class="modal modal-drawer fade" id="suppDetailModal" tabindex="-1" role="dialog" aria-labelledby="suppDetailModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Supplier</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" id='suppDetailDisp'>
                           

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

              </div><!-- /.page-section -->

              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="editSuppModal" tabindex="-1" role="dialog" aria-labelledby="editSuppModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Edit Supplier</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='editSuppPlace row' method='post' id='frmEditSupp' >
                                
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



      //Delete supplier
      $(document).on('click', '#del_cust', function(){
        var id = $(this).data('id');
        console.log('Delete supplier ID:', id); // Debug log
        
        Swal.fire({
          title: 'Ma hubtaa?',
          text: "Xogtan hadaa tirto lama soo celin karo",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Waan ogahay, Iga tir!', 
          cancelButtonText: 'Maya, waan ka laabtay'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:'../jquery/supplier.php',
              type:'post',
              data: 'del_supp='+id,
              success: function(data){
                data = data.trim(); // Trim whitespace
                console.log('Delete response:', data); // Debug log
                
                if(data == 'deleted'){
                  Swal.fire(
                    'La tiray!',
                    'Xogta iibiyaha waa la tiray.',
                    'success'
                  ).then(() => {
                    location.reload();
                  });
                }else{
                  console.log('Error details:', data); // Debug log
                  Swal.fire(
                    'Cilad!',
                    'Cilad ayaa jirto, markle isku day: ' + data,
                    'error'
                  )
                }
              },
              error: function(xhr, status, error){
                console.log('AJAX Error:', error); // Debug log
                Swal.fire(
                  'Network Error!',
                  'Fadlan mar kle isku day.',
                  'error'
                )
              }
            })
          }
        })
      })


      function get_suppliers(){
        $.ajax({
            url:'../jquery/supplier.php',
            type:'post',
            data: 'getSuppliers=24',
            success: function(data){
                $('tbody').html(data)
            }
        });
      }

      // Displaying customer detail
      $(document).on('click', '#view_supp', function(){
        var id = $(this).data('id')
        $('.overlay').removeClass('d-none');
        $.ajax({
            url:'../jquery/supplier.php',
            type:'post',
            data: 'viewSingleSupp='+id,
            success: function(data){
                $('#suppDetailDisp').html(data)
                $('.overlay').addClass('d-none');

            }
        });
      })

      // Edit supplier - displaying information
      $(document).on('click', '#edit_supp', function(){
        var id = $(this).data('id')
        console.log('Edit supplier clicked, ID:', id); // Debug log
        
        if(!id){
          toastr.error('Supplier ID not found');
          return;
        }
        
        $.ajax({
            url:'../jquery/supplier.php',
            type:'post',
            data: {editSingleSupp: id},
            beforeSend: function(){
              $('.editSuppPlace').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
            },
            success: function(data){
                console.log('Supplier data loaded'); // Debug log
                $('.editSuppPlace').html(data)
            },
            error: function(xhr, status, error){
                console.log('Error loading supplier:', error); // Debug log
                toastr.error('Failed to load supplier data');
                $('.editSuppPlace').html('<div class="alert alert-danger">Failed to load supplier data. Please try again.</div>');
            }
        });
      })

      //Updating Supplier Detail
      $(document).on('submit', '#frmEditSupp', function(e){
        e.preventDefault();
        console.log('Form submitted'); // Debug log
        
        $.ajax({
          url:'../jquery/supplier.php', 
          type:'post', 
          data: $('#frmEditSupp').serialize(),
          success: function(res){
            res = res.trim();
            console.log('Response:', res); // Debug log
            
            if(res == 'found_email'){
              toastr.error("Iimeylkan horay ayaa iibiye kale loogu diiwan geliyay, fadlan mid cusub geli")
              $('#cemail1').addClass('is-invalid')
            }else if (res == "phone_found"){
              toastr.error("Taleefan-kan horay ayaa iibiye kale loogu diiwan geliyay, fadlan mid cusub geli ")
              $('#cphone1').addClass('is-invalid')
            }else if (res == 'success'){
              toastr.success("Xogta iibiyaha waa la keydiyay")
              setTimeout(() => {
                location.reload();
              }, 1000);
              $('#frmEditSupp')[0].reset();
              $('#editSuppModal').modal('hide');
              $('#cphone1').removeClass('is-invalid')
              $('#cemail1').removeClass('is-invalid')
              get_suppliers();
            }else{
              console.log('Error details:', res); // Debug log
              toastr.error("Cilad ayaa jirta, fadlan mar kle isku day: " + res)
            }
          },
          error: function(xhr, status, error){
            console.log('AJAX Error:', error); // Debug log
            toastr.error("Network error. Fadlan mar kle isku day.")
          }
        })
      });




})

</script>
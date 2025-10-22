<?php include('../path.php'); ?>

<?php $title = 'Amaano list';  include(ROOT_PATH . '/inc/header.php'); ?>

      <?php $menu = 'Amaano'  ?>
      
      <?php $smenu = 'Liiska amaanada'  ?>
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
                  <h1 class="page-title"> Liiska amanada </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>amaano/new" class="btn btn-outline-info text-right"> <i class="fa fa-plus"></i> Diwaan geli amaano cusub </a>
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
                              <th>Nooca amaanada</th>
                              <th>Macaamiilka</th>
                              <th>Sheyga</th>
                              <th>Tirada</th>
                              <th>La bixiyay</th>
                              <th>Haraa</th>
                              <th>Taarikh</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php 
                            $count = 0;
                            $stmt = "SELECT amano_id, amano_date,amaano.name as aname,tran_date, total, given, amaano.description, remain,customer.cust_name, amaano_type.name as atname, users.fullname from amaano, customer, users, amaano_type WHERE amaano.customer_id = customer.customer_id AND amaano_type.amanotid = amaano.type ORDER BY amaano.amano_id DESC";
                            $result = mysqli_query($con, $stmt); 
                            while($row = mysqli_fetch_assoc($result)){
                              $count ++; 
                              $id = $row['amano_id'];
                              $name = $row['aname'];
                              $customer = $row['cust_name'];
                              $total = $row['total'];
                              $given = $row['given'];
                              $remain = $row['remain'];
                              $type = $row['atname'];
                      
                              $date = date('d/m/Y', strtotime($row['amano_date'])) ;

                              echo "

                              <tr>
                                <td>$count</td>
                                <td>$type</td>
                                <td>$customer</td>
                                <td>$name</td>
                                <td>$total</td>
                                <td>$given</td>
                                <td>$remain</td>
                                <td>$date</td>
                                <td>
                                <div class='dropdown d-inline-block'>
                                  <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                  <div class='dropdown-menu dropdown-menu-right' style=''>
                                    <div class='dropdown-arrow'></div>
                                    <a href='' type='button' class='dropdown-item' id='btnEamaano' data-id='$id' data-toggle='modal' data-target='#editAmaanoDetail'> <i class='fa fa-edit'></i> Wax ka bedel amaanada</a>
                                    <button type='button' class='dropdown-item btn-danger' id='delAmaano' data-id='$id'><i class='fa fa-trash'></i> Tir amaanada</button>
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
             


                      <!-- Edit amaano drawer -->
                      <div class="modal modal-drawer fade" id="editAmaanoDetail" tabindex="-1" role="dialog" aria-labelledby="editAmaanoDetail" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay " id='loadedit'>
                              <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Wax ka badalid amaano</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                              <form class='row' method='post' id='frmAmano'>
                                <div class='form-group col-md-4'>
                                  <label for=''> Macaamiil</label>
                                    <div class='input-group input-group-alt'> 
                                      <select name='customer' id='customer' class='form-control customer' data-toggle='selectpicker' required data-live-search='true' >
                                        <option data-tokens='' value=''>Dooro macaamiil  </option>
                                        
                                       
                                      </select>
                                      <div class='input-group-prepend'>
                                        <button class='btn btn-secondary' title='Add new customer' type='button' data-toggle='modal' data-target='#addCustModal'><i class='fa fa-plus'></i></button>
                                      </div>
                                    </div>
                                    <p class='text-danger d-none' id='custMsg'></p>
                                </div>

                                <div class='form-group col-md-4'>
                                  <label for=''>Nooca amaanada </label>
                                  <div class='input-group input-group-alt'> 
                                      <select name='amanotype' id='amanotype' class='form-control amanotype' data-toggle='selectpicker' required data-live-search='true' >
                                        <option data-tokens='' value=''>Dooro macaamiil  </option>
                                       
                                      </select>
                                      <div class='input-group-prepend'>
                                        <button class='btn btn-secondary' title='Abuur nooc amaano cusub' type='button' data-toggle='modal' data-target='#addAtypeModal'><i class='fa fa-plus'></i></button>
                                      </div>
                                    </div>

                                </div>

                                <div class='form-group col-md-4'>
                                  <label for=''>Taarikhda la qabtay</label>
                                  <input id='flatpickr03' type='hidden' placeholder='-- Dooro -- ' required name='adate' id='adate' class='form-control flatpickr-input rounded-0 sdate' data-toggle='flatpickr' required data-alt-input='true' data-alt-format='F j, Y' data-date-format='Y-m-d'>
                                  
                                </div>

                                <div class='form-group col-md-4'>
                                  <label for=''>Sheyga amaanada</label>
                                  <input type='text' name='aname' id='aname' class='form-control' required>
                                </div>

                                <div class='form-group col-md-3'>
                                  <label for=''>Tirada</label>
                                  <input type='number' name='qty' id='qty' class='form-control' required>
                                </div>

                                <div class='form-group col-md-5'>
                                  <label for=''>Faahfaahin</label>
                                  <input type='text' name='desc' id='desc' class='form-control' required>
                                </div>

                                <div class='form-group col-md-12'>
                                  <center>
                                    <button type='submit' class='btn btn-info rounded-6'>Ii Keydi xogta</button>
                                  </center>
                                </div>
                              </form>
                              </div>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class='modal-footer'>
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
      $(document).on('click', '#delAmaano', function(){
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
                  url:'../jquery/amaano/list.php',
                  type:'post',
                  data: 'delAmaano='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Waa lagu guuleystay in la tiro xogta.',
                        'success'
                      )
                     setTimeout(() => {
                      location.reload();
                     }, 2500); 
                    }else{
                      Swal.fire(
                        'Error!',
                        'Waan ka xunahay xogtan lama tiro karo.',
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
      $('#frmEditCust').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/customer.php', 
          type:'post', 
          data: $('#frmEditCust').serialize(),
          success: function(res){
            if(res == 'found_email'){
              toastr.error("This email account is allready with another customer, try another one")
              $('#cemail1').addClass('is-invalid')
            }else if (res == "phone_found"){
              toastr.error("This phone number is allready registered with another customer, try another one ")
              $('#cphone1').addClass('is-invalid')
            }else if (res == 'success'){
              toastr.success("Customer updated successfully")
              setTimeout(() => {
                location.reload();
                
              }, 1000);
              $('#frmEditCust')[0].reset();
              $('#editAmaanoDetail').modal('hide');
              $('#cphone1').removeClass('is-invalid')
              $('#cemail1').removeClass('is-invalid')
              get_customers();

            }
          }
        })
      });


      /// Get Amaano information 
      $(document).on('click', '#btnEamaano', function(){
        var id = $(this).data('id')
        console.log(id)
        $.ajax({
          url:'../jquery/amaano/list.php', 
          type:'post', 
          data: 'editAmaanoInfo='+id ,
          success: function(res){
            $('#frmAmano').html(res)
            $('#loadedit').addClass('d-none')
          }
        })
      });

      // Updationg amano information
      $('#frmAmano').on('submit', function(e){
        e.preventDefault();
        $('#loadedit').removeClass('d-none')
        $.ajax({
            url:'../jquery/amaano/list.php',
            type:'post',
            data: $('#frmAmano').serialize(),
            success: function(res){
               if(res == "success"){
                $('#loadedit').addClass('d-none')
                toastr.success("Waala keydiyay xogta")
                $('#frmAmano')[0].reset();
                $('#editAmaanoDetail').modal('hide');

                setTimeout(() => {
                  window.location.replace("<?= BASE_URL ?>amaano/list");
                  
                }, 2500);

               }

            }
        });
      })



})

</script>
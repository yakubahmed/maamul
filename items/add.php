<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add Item"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Alaabta'  ?>
      
      <?php $smenu = 'Ku dar shay'  ?>
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
                  <h1 class="page-title"> Item registration </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>items/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Items </a>
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
                        
                        <form class='row' method='post' id='frmAddItem' enctype='multipart/form-data' >

                          <div class='form-group col-md-4'>
                            <label for=''> Item name *</label>
                            <div class='has-clearable'>
                              <button type='button' class='close' aria-label='Close'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span></button>
                              <input type='text' name='iname' id='iname' class='form-control rounded-0' autocomplete='off' required placeholder='Enter item name'>
                            </div>
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''>Category *</label>
                            <select id='bss4 category' name='category' data-toggle='selectpicker' required data-live-search='true' data-width='100%'>
                              <option data-tokens='' value=''>Select Categoty  </option>
                              <?php 
                              $stmt = 'SELECT * FROM item_category ORDER BY category_name ASC';
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $id = $row['itemcat_id'];
                                $name = $row['category_name'];

                                echo "
                                    <option data-tokens='$name' value='$id'>$name  </option>
                                ";
                              }
                              
                              ?>
                            </select>
                          </div>
                          <div class='form-group col-md-4'>
                            <label class='control-label' for='bss4'>Unit *</label> 
                            <select id='bss4'  name = 'unit' data-toggle='selectpicker' data-live-search='true' data-width='100%' required>
                              <option data-tokens='' value=''> Select unit  </option>
                              <?php 
                                $stmt = 'SELECT * FROM unit ORDER BY unit_name ASC';
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  $id = $row['unit_id'];
                                  $uname = $row['unit_name'];
                                  $sname = $row['shortname'];
                                  echo "
                                      <option data-tokens='$sname' value='$id' data-id='$sname'> $uname  </option>
                                  ";
                                }
                              ?>
                            </select>
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Quantity *</label>
                            <div class='input-group input-group-alt rounded-0'>
                              <input type='number' min='1' class='form-control rounded-0' id='pi3' name='qty' placeholder='Enter quantity' required>
                              <div class='input-group-prepend'>
                                <span class='input-group-text d-none ' id='qty_postfix'></span>
                              </div>
                            </div>
                            
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Purchase price *</label>
                            <div class='input-group rounded-0'>
                            <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='pprice' placeholder='Enter purchase price' step="0.01" required class="form-control rounded-0" id="pi9">
                            </div>
                            
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Sale price *</label>
                            <div class="input-group rounded-0">
                              <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='sprice' placeholder='Enter Sale price' step="0.01" required class="form-control rounded-0" id="pi9">
                            </div>
                          </div>

                          <div class="form-group col-md-4">
                            <label class="control-label" for="flatpickr03">Recieved date</label> 
                            <input id="flatpickr03" type="hidden" name='rdate' placeholder='Enter item recieved date' class="form-control flatpickr-input rounded-0" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                          </div>

                          <div class="form-group col-md-8">
                            <label for="">Barcode</label>
                            <div class="has-clearable">
                              <button type="button" class="close" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span></button>
                              <input type="text" name="barcode" id="barcode" class="form-control rounded-0" autocomplete="off" placeholder="Enter barcode or leave empty for auto-generation">
                            </div>
                          </div>

                          <div class="form-group col-md-8">
                            <label for="">Item image</label>
                            <input type="file" name="iimage" id="iimage" class="form-control">
                          </div>


                          <div class='form-group col-md-12 text-center'>
                            <button type='submit' class='btn btn-info rounded-0'> <i class="fa fa-save"></i> Save item</button>
                            <button type='reset' class='btn btn-danger rounded-0'> <i class="fa fa-reload"></i> Reset</button>
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

  $('#frmAddItem').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/add-item.php', 
      type:'post', 
      cache: false,
      contentType: false, // you can also use multipart/form-data replace of false
      processData: false,
      data: new FormData(this),
      success: function(res){
        res = res.trim(); // Trim whitespace
        console.log('Add item response:', res); // Debug log
        
        if(res == 'found_product'){
          toastr.error("This product is already registered, try another one")
          $('.overlay').addClass('d-none')
          $('#iname').addClass('is-invalid')
        }else if(res == 'found_barcode'){
          toastr.error("This barcode is already in use, try another one")
          $('.overlay').addClass('d-none')
          $('#barcode').addClass('is-invalid')
        }else if (res == 'success'){
          toastr.success("Item registered successfully")
          $('#frmAddItem')[0].reset();
          $('.overlay').addClass('d-none')
          $('#iname').removeClass('is-invalid')
          $('#barcode').removeClass('is-invalid')
          
          // Refresh selectpicker if it exists
          if($('.selectpicker').length){
            $('.selectpicker').selectpicker('refresh');
          }
          
          setTimeout(function(){
            window.location.replace("<?= BASE_URL ?>items/list")
          }, 1000);
        }else if(res.startsWith('database_error:')){
          toastr.error("Database error: " + res.replace('database_error: ', ''))
          $('.overlay').addClass('d-none')
          console.log('Database error details:', res)
        }else if(res == 'no_permission'){
          toastr.error("You don't have permission to add items")
          $('.overlay').addClass('d-none')
        }else if(res == 'missing_fields'){
          toastr.error("Please fill in all required fields")
          $('.overlay').addClass('d-none')
        }else if(res == 'invalid_values'){
          toastr.error("Please enter valid values (quantities and prices must be positive)")
          $('.overlay').addClass('d-none')
        }else if(res == 'failed'){
          toastr.error("Failed to register item. Please try again.")
          $('.overlay').addClass('d-none')
        }else{
          toastr.error("Failed to register item: " + res)
          $('.overlay').addClass('d-none')
          console.log('Error details:', res)
        }
      },
      error: function(xhr, status, error){
        toastr.error("Network error: " + error)
        $('.overlay').addClass('d-none')
        console.log("AJAX Error:", xhr.responseText)
      }
    })
  })


})

</script>
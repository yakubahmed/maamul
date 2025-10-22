<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "New Amaano"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->

      <?php $menu = 'Amaano'  ?>
      
      <?php $smenu = 'Amaano cusub'  ?>
      
      
      
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
                  <h1 class="page-title"> Amaano cusub </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>amaano/list" class="btn btn-outline-info text-right"> <i class="fa fa-users"></i> Liiska amaanada </a>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card ">
                      <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
                      
                      <div class="card-body ">
                        
                        <form class='row' method='post' id='frmAmano'>

                          <div class='form-group col-md-4'>
                            <label for=''> Macaamiil</label>
                              <div class="input-group input-group-alt"> 
                                <select name="customer" id="customer" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >
                                  <option data-tokens='' value=''>Dooro macaamiil  </option>
                                  
                                  <?php
                                    $stmt = "SELECT * FROM customer WHERE customer_id != 29 ORDER BY cust_name ASC";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_assoc($result)){
                                      $id = $row['customer_id'];
                                      $name = $row['cust_name'];
                                      $phone = $row['cust_phone'];
                                      echo "
                                        <option data-tokens='$phone' value='$id'>$name  </option>
  
                                      ";
                                    }
                                  ?>
                                </select>
                                <div class="input-group-prepend">
                                  <button class="btn btn-secondary" title='Add new customer' type="button" data-toggle='modal' data-target='#addCustModal'><i class="fa fa-plus"></i></button>
                                </div>
                              </div>
                              <p class='text-danger d-none' id='custMsg'></p>
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Nooca amaanada </label>
                            <div class="input-group input-group-alt"> 
                                <select name="amanotype" id="amanotype" class="form-control amanotype" data-toggle='selectpicker' required data-live-search='true' >
                                  <option data-tokens='' value=''>Dooro macaamiil  </option>
                                  <?php 
                                  
                                    $stmt = "SELECT * FROM amaano_type ORDER BY name ASC";
                                    $result = mysqli_query($con, $stmt); 
                                    while($row = mysqli_fetch_assoc($result)){
                                      $id = $row['amanotid'];
                                      $name = $row['name'];

                                      echo "
                                        <option data-tokens='$id' value='$id'>$name  </option>
                                      ";
                                    }
                                    
                                  ?>
                                </select>
                                <div class="input-group-prepend">
                                  <button class="btn btn-secondary" title='Abuur nooc amaano cusub' type="button" data-toggle='modal' data-target='#addAtypeModal'><i class="fa fa-plus"></i></button>
                                </div>
                              </div>
                        
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Taarikhda la qabtay</label>
                            <input id="flatpickr03" type="hidden" placeholder="-- Dooro -- " required name='adate' id='adate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                            
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Sheyga amaanada</label>
                            <input type="text" name="aname" id="aname" class="form-control" required>
                          </div>

                          <div class="form-group col-md-3">
                            <label for="">Tirada</label>
                            <input type="number" name="qty" id="qty" class="form-control" required>
                          </div>

                          <div class="form-group col-md-5">
                            <label for="">Faahfaahin</label>
                            <input type="text" name="desc" id="desc" class="form-control" required>
                          </div>

                          <div class='form-group col-md-12'>
                            <center>
                              <button type='submit' class='btn btn-info rounded-6'>Ii Keydi xogta</button>
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


    <!-- Models -->

      <!-- Add customer drawer -->
      <div class="modal modal-drawer fade" id="addCustModal" tabindex="-1" role="dialog" aria-labelledby="addCustModal" aria-hidden="true">
            <!-- .modal-dialog -->
            <div class="modal-dialog modal-lg modal-drawer-right" role="document">
              <!-- .modal-content -->
              <div class="modal-content bg-light">

                <!-- .modal-header -->
                <div class="modal-header">
                  <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Add customer</strong> </h5>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body" >
                
                <form class='row' method='post' id='frmCust'>

                  <div class='form-group col-md-6'>
                    <label for=''> Customer name *</label>
                    <input type='text' name='cname' id='cname' class='form-control rounded-0' required>
                  </div>

                  <div class='form-group col-md-6'>
                    <label for=''> Phone number *</label>
                    <input type='text' name='cphone' id='cphone' class='form-control  rounded-0' required>
                  </div>

                  <div class='form-group col-md-4'>
                    <label for=''> Email Address</label>
                    <input type='text' name='cemail' id='cemail' class='form-control rounded-0'>
                  </div>

                  
              <div class='form-group col-md-4'>
                <label for=''> Balance</label>
                <input type='text' name='balance' id='balance' class='form-control rounded-0' placeholder='Enter customer balance' autocomplete='off'>
              </div>

                  <div class='form-group col-md-4'>
                    <label for=''> Status *</label>
                    <select name='status' id='status' class='form-control'>
                      <option value=''> Select status</option>
                      <option value='Active'> Active</option>
                      <option value='Disabled'> Disabled</option>
                    </select>
                  </div>

                  <div class='form-group col-md-12'>
                    <label for=''> Address</label>
                    <textarea name='addr' id='addr' class='form-control rounded-0'></textarea>
                  </div>

                  <div class='form-group col-md-6'>
                    <button type='submit' class='btn btn-info rounded-0'>Save customer</button>
                  </div>
                </form>

                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div><!-- /.modal-footer -->
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->


              
    <!-- Add Amaano type drawer -->
    <div class="modal modal-drawer fade" id="addAtypeModal" tabindex="-1" role="dialog" aria-labelledby="addAtypeModal" aria-hidden="true">
            <!-- .modal-dialog -->
            <div class="modal-dialog modal-lg modal-drawer-right" role="document">
              <!-- .modal-content -->
              <div class="modal-content bg-light">

                <!-- .modal-header -->
                <div class="modal-header">
                  <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Diwaan geli nooca<small> amaanada</small> </strong> </h5>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body" >
                
                <form class='row' method='post' id='frmAmanoT'>

                      <div class='form-group col-md-12'>
                          <label for=''>Magaca </label>
                          <input type='text' name='atname' id='atname' class='form-control'  maxlength="50" required>
                      </div>

                      <div class='form-group col-md-12'>
                          <label for=''>Faah faahin</label>
                          <textarea name='cesc' class='form-control' id='desc' required maxlength="200"></textarea>
                      </div>
                

                      <div class='form-group col-md-12'>
                        <center>
                          <button type='submit' class='btn btn-info rounded-6'>Ii keydi xogta</button>
                        </center>
                      </div>
                </form>

                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div><!-- /.modal-footer -->
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

//Customer registration 
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
          toastr.error("This email account is allready with another customer, try another one")
          $('.overlay').addClass('d-none')
          $('#cemail').addClass('is-invalid')
        }else if (res == "phone_found"){
          $('.overlay').addClass('d-none')
          toastr.error("This phone number is allready registered with another customer, try another one ")
          $('#cphone').addClass('is-invalid')
        }else if (res == 'success'){
        $('#addCustModal').modal('hide');
         $('.overlay').addClass('d-none')
         $('#frmCust')[0].reset();


        $(document).ready(function() {
            $.ajax({
              url: '../jquery/amaano/new.php',
              type: 'POST',
              data:'new-customer',
              dataType: 'json',
              success: function(response) {
                $('#cust').empty();
                if (response && response.data) {
                  $.each(response.data, function(index, value) {
                    $('#cust').append('<option value="' + value.id + '">' + value.name + '</option>');
                  });
                  $('#cust').selectpicker('refresh');
                } else {
                  console.error('No data received.');
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching data:', textStatus, errorThrown);
              }
            });
          });
          
        }else{
          toastr.error("Something is wrong please try again")
          console.log(res);
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')
        }
      }
    })
  })

// Amaano type registration 
  $('#frmAmanoT').on('submit', function(e){
    e.preventDefault();

    $('.load1').removeClass('d-none')
    $.ajax({
      url:'../jquery/amaano/type.php', 
      type:'post', 
      data: $('#frmAmanoT').serialize(),
      success: function(res){
        toastr.success("Xogta nooca amaanada waala keediyay")
        $('#addAtypeModal').modal('hide');
        $('#frmAmanoT')[0].reset();
        $(document).ready(function() {
            $.ajax({
              url: '../jquery/amaano/new.php',
              type: 'POST',
              data: 'new-amaanot',
              dataType: 'json',
              success: function(response) {
                $('#amanotype').empty();
                if (response && response.data) {

                  $.each(response.data, function(index, value) {
                    $('#amanotype').append('<option value="' + value.id + '">' + value.name + '</option>');
                  });
                  $('#amanotype').selectpicker('refresh');
                } else {
                  console.error('No data received.');
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching data:', textStatus, errorThrown);
              }
            });
          });

        
      }
    })
  })
  
  
  // Creation of new amaano 
  $('#frmAmano').on('submit',function(e){
    e.preventDefault();
    $('overlay').removeClass('d-none')

    var customer  = $('#customer').val()
    var amaanotype  = $('#amanotype').val()
    var date  = $('#adate').val()
    var aname  = $('#aname').val()
    var qty  = $('#qty').val()
    var desc  = $('#desc').val()

    // Checking if all fields are filled

    if(customer === "" || amaanotype === "" || date === "" || aname === "" || desc === ""){
      toastr.error("Faldan foomka wada buuxi")
      $('overlay').addClass('d-none')
    }else{

      $.ajax({
          url: '../jquery/amaano/new.php',
          type: 'POST',
          data: $('#frmAmano').serialize(),
          success: function(res) {
              if(res == 'success'){
                toastr.success("Waa lagu guuleystay in la keydiyo xogta")
                $('#frmAmano')[0].reset();

                setTimeout(() => {
                  window.location.replace("<?= BASE_URL ?>amaano/list");
                  
                }, 1500);
              }else{
                toastr.error("Cilad ayaa jirto, fadlan mar kle isku day")
                console.log(res)
              }
          },
         
      });

    }


  })
  
  


})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Amaano Bixin"; include(ROOT_PATH . '/inc/header.php'); ?>
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
      
      <?php $smenu = 'Amaano bixin'  ?>
      
      
      
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
                  <h1 class="page-title"> Bixi amaano </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>amaano/bixin-list" class="btn btn-outline-info text-right"> <i class="fa fa-list"></i> Liiska amaanada la bixiiyay</a>
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

                          <div class='form-group col-md-6'>
                            <label for=''> Macaamiil</label>
                              <div class="input-group input-group-alt"> 
                                <select name="customer" id="customer" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >
                                  <option data-tokens='' value=''> -- Dooro macaamiil -- </option>
                                  
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
                              
                              </div>
                              <p class='text-danger d-none' id='custMsg'></p>
                          </div>

                          <div class="form-group col-md-6">
                            <label for="">Sheyga amaanada </label>
                            <div class="input-group input-group-alt"> 
                                <select name="amanoitem" id="amanoitem" class="form-control amanotype" data-toggle='selectpicker' required data-live-search='true' >
                                  <option data-tokens='' value=''>-- Dooro sheyga --  </option>
                           
                                </select>
                              
                              </div>
                        
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Tirada la bixiyay</label>
                            <input type="number" name="given" id="given" step='0.02' class="form-control">
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Taarikhda la bixiyay</label>
                            <input id="flatpickr03" type="hidden" placeholder="-- Dooro -- " required name='adate' id='adate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Tirada hartay</label>
                            <input type="hidden" name="bal" id='bal'>
                            <input type="text" name="balance" id="balance" class="form-control" readonly>
                          </div>

                          <div class="form-group col-md-12">
                            <label for="">Faah faahin</label>
                            <textarea name="desc" id="desc" class='form-control'></textarea>
                          </div>


                       

                          <div class='form-group col-md-12'>
                            <center>
                              <button type='submit' class='btn btn-info rounded-6'>Bixi amaanada</button>
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

  
  //Filling sheyga amaanada
  $(document).on('change', '#customer', function(){
    var id = $(this).val();
 
    $(document).ready(function() {
      if(id != ""){
        $.ajax({
          url: '../jquery/amaano/bixin.php',
          type: 'POST',
          data:'amaano='+id,
          dataType: 'json',
          success: function(response) {
            $('#amanoitem').empty();
            if (response && response.data) {
              
              $.each(response.data, function(index, value) {
                $('#amanoitem').append('<option value="' + value.id + '">' + value.name + '</option>');
              });
              $('#amanoitem').selectpicker('refresh');
            } else {
            $('#amanoitem').empty();
  
              console.error('No data received.');
            }



          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching data:', textStatus, errorThrown);
          }
        });

      }
    });
  })


  // Creation of new amaano 
  $('#frmAmano').on('submit',function(e){
    e.preventDefault();
    $('overlay').removeClass('d-none')

    var customer  = $('#customer').val()
    var item  = $('#amanoitem').val()
    var date  = $('#adate').val()
    var bal  = $('#balance').val()
    var desc  = $('#desc').val()

    // Checking if all fields are filled

    if(customer === "" || item === "" || date === "" || desc === "" ){
      toastr.error("Faldan foomka wada buuxi")
      $('overlay').addClass('d-none')
    }else{

      $.ajax({
          url: '../jquery/amaano/bixin.php',
          type: 'POST',
          data: $('#frmAmano').serialize(),
          success: function(res) {
              if(res == 'success'){
                toastr.success("Waa lagu guuleystay in la keydiyo xogta")
                $('#frmAmano')[0].reset();

                setTimeout(() => {
                  window.location.replace("<?= BASE_URL ?>amaano/bixin-list");
                  
                }, 1500);
              }else{
                toastr.error("Cilad ayaa jirto, fadlan mar kle isku day")
                console.log(res)
              }
          },
         
      });

    }


  })
  
  
// Item on change
  $(document).on('change', '#amanoitem', function(){
    var id  = $(this).val()

    $.ajax({
          url: '../jquery/amaano/bixin.php',
          type: 'POST',
          data: 'amaanoi_change='+id,
          success: function(data) {
             $('#balance').val(data)
             $('#bal').val(data)
          },
         
      });
  });

  //Given amount change 
  $(document).on('change', '#given', function(){
    var giv = $(this).val();
    var total = $('#bal').val()
    var remain = total - giv

    $('#balance').val(remain)
  })


})

</script>
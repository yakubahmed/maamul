<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add Delivery note"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Delivery note'  ?>
      
      <?php $smenu = 'Add delivery note'  ?>
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
                  <h1 class="page-title"> Add Delivery <small>note</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="<?= BASE_URL ?>customer/list" class="btn btn-info text-right"> <i class="fa fa-users"></i> View Customers </a> -->
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light " style=''>
                      <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
                      
                      <div class="card-body ">


                        
                        <form class='row' method='post' id='frmShowRep'>

                        <div class="form-group col-md-4">
                            <label for="">INVOICE NUMBER *</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  INV-
                                </div>
                              </div>
                              <input type="text" class="form-control" aria-label="" name='invoicenum' id='invoicenum' autocomplete='off' required>
                            
                            </div>
                          </div>
                          
                          <div class="form-group col-md-4">
                            <label for="">Delivery method *</label>
                            <select name="dmeth" id="dmeth" class="form-control pmeth" data-toggle='selectpicker' required data-live-search='true' >
                                <option data-tokens='' value=''>Select delivery method  </option>
                                <?php
                                  $i = 0;
                                  $stmt = "SELECT * FROM delivery_method ";
                                  $result = mysqli_query($con, $stmt);
                                  while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['del_meth_id'];
                                    $name = $row['meth_name'];
                                    $des = $row['description'];
                                    $date = date("M d, Y", strtotime($row['date']));
                                    if($des == "" ){ $des = "N/A"; }

                                    echo "
                                      <option data-tokens='$name' value='$id'>  $name</option>
                                      
                                    ";

                                  }

                                
                                ?>
                            </select>
                          </div>

                          <div class="form-group col-md-4">
                            <label for="">Despatch date *</label>
                            <input id="ddate" type="hidden" required placeholder="Select Despatch date" name='ddate'  class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d" required>
                            
                          </div>




                            <div class="form-group col-md-12">
                              <center>
                                <button type="submit" class='btn btn-info btn-block col-md-3 rounded-0'> <i class="fa fa-search"></i> Show</button>
                              </center>

                            </div>

                            

                        
                        </form>

                        <div class="card-body d-none" id='resp'>
                            <div class="table-responsive">
                                <table class="table table-striped ">
                                    <thead>
                                        <tr class='bg-success text-light'>
                                            <th style="min-width:70px">#</th>
                                            <th style='min-width:200;'>Item name</th>
                                            <th>Ordered</th>
                                            <th >Delivered</th>
                                            <th >Outstanding</th>
                                            
                                        </tr>
                                        
                                    </thead>
                                    <tbody id='res'>
                                       
                                    </tbody>
                                  
                                </table>
                            </div>
                            <div class="form-group col-md-12">
                                  <center>
                                    <button type="submit" id='btnSaveDel' class='btn btn-success btn-block col-md-3 rounded-0'> <i class="fa fa-save"></i> Save changes</button>
                                  </center>
    
                                </div>
                        </div>
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

  <!-- Alert Danger Modal -->
  <div class="modal modal-alert fade" id="msgModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalAlertDangerLabel" aria-hidden="true">
    <!-- .modal-dialog -->
    <div class="modal-dialog modal-dialog-centered" role="document">
      <!-- .modal-content -->
      <div class="modal-content">
        <!-- .modal-header -->
        <div class="modal-header">
          <h5 id="exampleModalAlertDangerLabel" class="modal-title">
            <i class="fa fa-exclamation-triangle text-red mr-1"></i> Error </h5>
        </div><!-- /.modal-header -->
        <!-- .modal-body -->
        <div class="modal-body">
          <h3> <center>Invoice not found </center> </h3>
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
        </div><!-- /.modal-footer -->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

  $('#frmShowRep').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    
    $.ajax({
      url:'../jquery/delivery.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        
        // Handle JSON response for session expired
        if(typeof res === 'object' && res.error === 'Session expired'){
          toastr.error("Your session has expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php'
          }, 2000);
          return;
        }
        
        // Handle JSON responses
        if(typeof res === 'object'){
          if(res.error === 'not_logged_in'){
            toastr.error("You must be logged in to view delivery information")
            window.location.href = '<?= BASE_URL ?>login.php'
          }else{
            toastr.error("An error occurred while checking the invoice")
            console.error('Unexpected response:', res)
          }
        }else{
          // Handle legacy string responses (fallback)
          if(res == 'not-found'){
            $('#msgModal').modal();
          }else{
            $('#resp').removeClass('d-none')
            $('#res').html(res)
          }
        }
      },
      error: function(xhr, status, error){
        $('.overlay').addClass('d-none')
        toastr.error("Network error occurred. Please check your connection.")
        console.error('AJAX Error:', error)
      }
    })
  })


  $(document).on('blur', '.delivered', function(){
    var id = $(this).data("id"); 
    var del = $(this).val()

    var qty = $('#qty'+id).val()
    console.log(qty)
    var balance = qty - del
    
      $('#balance'+id).val(balance)

   if(del > qty){
    if(del > qty){
      toastr.error("The maximum amount you can deliver is "  + qty )
      $(this).val(qty)
      $('#balance'+id).val(0)

    }
   }


  });

  $(document).on('click', '#btnSaveDel', function(){

    var invoice = $('#invoicenum').val()
    var dmeth = $('#dmeth').val()
    var ddate = $('#ddate').val()

    var delid = [];
    $('input[name="delivered[]"]').each( function() {
      delid.push(this.value);
    });

    var balid = [];
    $('input[name="balance[]"]').each( function() {
      balid.push(this.value);
    });

    var iid = [];
    $('input[name="item_id[]"]').each( function() {
      iid.push(this.value);
    });

  var custid = $('#cust_id').val()



    var qty = [];
    $('input[name="qty[]"]').each( function() {
      qty.push(this.value);
    });
    
    var unit = [];
    $('input[name="unit_id[]"]').each( function() {
      unit.push(this.value);
    });



    $.ajax({
            url: '../jquery/delivery.php',
            type: 'post',
            dataType: 'json',
            data: {invoice: invoice, dmeth: dmeth, ddate:ddate, delid:delid, balid:balid, iid:iid, qty:qty, custid:custid, unit_id:unit },
            success: function(res){
                // Handle JSON response for session expired
                if(typeof res === 'object' && res.error === 'Session expired'){
                  toastr.error("Your session has expired. Please login again.")
                  setTimeout(function(){
                    window.location.href = '<?= BASE_URL ?>login.php'
                  }, 2000);
                  return;
                }
                
                // Handle JSON responses
                if(typeof res === 'object'){
                  if(res.error === 'not_logged_in'){
                    toastr.error("You must be logged in to create delivery notes")
                    window.location.href = '<?= BASE_URL ?>login.php'
                  }else if(res.error === 'missing_fields'){
                    toastr.error("Please fill in all required fields")
                  }else if(res.success === true){
                    window.location.replace("<?= BASE_URL ?>delivery-note/list");
                  }else{
                    toastr.error("An unexpected error occurred. Please try again.")
                    console.error('Unexpected response:', res)
                  }
                }else{
                  // Handle legacy string responses (fallback)
                  if(res == "success"){
                    window.location.replace("<?= BASE_URL ?>delivery-note/list");
                  }else{
                    toastr.error("Something is wrong please try again")
                    console.log(res)
                  }
                }
            },
            error: function(xhr, status, error){
              toastr.error("Network error occurred. Please check your connection.")
              console.error('AJAX Error:', error)
            }
    });


  });



})

</script>
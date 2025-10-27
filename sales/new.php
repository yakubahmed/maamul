<?php include('../path.php'); ?>


<style>
 input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}
</style>

<audio id="audio" src="<?= BASE_URL ?>assets/sound/add.mp4"></audio>

<?php $title = "New Sale"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Iib'  ?>
      
      <?php $smenu = 'Iib cusub'  ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php //$isactive = 'sales'; ?>
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
                  <h1 class="page-title"> New Sale </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>sales/history" class="btn btn-success text-light"> <i class="fa fa-list"></i> Sales history </a>
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
                  
                          <form class='row' method='post' id='frmAddCust'>
  
                            <!-- <div class='form-group col-md-4'>
                              <label for=''> Invoice number</label>
                              <input type="hidden" name="order_num" id="order_num">
                              <input type='text' name='inum' id='inum' class='form-control rounded-0' required readonly value='SL-008'>
                            </div> -->
                            
                            <div class='form-group col-md-8'>
                              <label for=''> Customer *</label>
                              <div class="input-group input-group-alt">
                              <input type="hidden" name="order_num" id="order_num">
  
                                <select name="customer" id="cust" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >
                                  <option data-tokens='' value=''>Select customer  </option>
                                  
                                  <?php
                                    $stmt = "SELECT * FROM customer ORDER BY cust_name ASC";
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
  
                            <div class='form-group col-md-4'>
                              <label for=''> Sale date *</label>
                              <input id="flatpickr03" type="hidden" placeholder="Select sale date" name='sdate' id='sdate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                              <p class='text-danger d-none' id='sdateMsg'></p>
                            </div>
  
                            <div class="form-group col-md-4">
                              <label for="">Order Status</label>
                              <select name="status" id="status" class="form-control rounded-0">
                                <option value="">Select order status</option>
                                <option value="Ordered">Ordered</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Delivered">Delivered</option>
                              </select>
                              <p class='text-danger d-none' id='statusMsg'></p>
                            </div>
  
                            <div class="form-group col-md-4">
                              <label for="">Reference  (PO)</label>
                              <input type="text" name="porder" id="porder" class="form-control rounded-0" placeholder='Enter purchase order number'>
                            </div>
  
                            <div class="form-group col-md-4" id='paymentDeadline'>
                              <label for="">Due date </label>
                              <input id="pdateline" type="hidden" placeholder="Select due date" name='pdateline' id='pdateline' class="form-control flatpickr-input rounded-0 pdateline" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
  
                            </div> 
  
                            <div class="col-md-12">
                              <hr>
                            </div>
  
                            <div class="card col-md-12 my-2">
                              <div class="card-body">
                                <div class="form-group col-md-12 my-2">
                                <div class="input-group has-clearable input-group-lg rounded-0">
                                 <input type="search" autocomplete='off' name="item_search" id="item_search" class="form-control form-control-lg rounded-0  ui-autocomplete-input" placeholder='Search product / item'> <label class="input-group-append" for="ai1"><span class="input-group-text"><span class="fa fa-search"></span></span></label>
                                </div>
                                
                                <ul class="list-group sear_pro_res d-none">
        
                               </ul>
                                
      
                                </div>
      
                                <div class="container table-responsive">
                                  <table class='table  table-bordered  my-3 col-md-12' style='width:100%'>
                                    
                                      <thead>
                                          <tr class='bg-primary text-light'>
                                              <th style='min-width:250px;'>Item name</th>
                                              <th style='min-width:140px;'>Quantity</th>
                                              <th style='min-width:90px;'>Unit price</th>
                                              <th style='min-width:90px;'>Discount</th>
                                              <th style='min-width:90px;'>Total</th>
                                              <th style='min-width:90px;'>Action</th>
                                          </tr>
                                      </thead>
                                      <div class="overlay d-none">
                                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                                      </div>
                                      <tbody id='order_list'>
                                          <tr>
                                              <td colspan='6'> <p class="text-center"><strong>No item added on the list</strong></p> </td>
                                          </tr>
        
                                      </tbody>
                                      <!-- <tfoot>
                                          <tr class='bg-primary text-light'>
                                              <td colspan='4' class=' text-right'> <strong>Sub Total</strong> </td>
                                              <td colspan='2' class=''>   <strong id='totalamount'>0.00 $ </strong> </td>
                                          </tr>
                                      </tfoot> -->
                                    </table>
      
                                </div>

                              </div>
                            </div>
  
                          </form>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
              
                              <div class="form-group col-md-12 ">
                                <label for="">Discount on all</label>
                                <input type="number" name="disc_total" steps='0.2' id="disc_total" value='0' class="form-control">
                              </div>

                            </div>

                            <div class="col-md-6 text-right">
                                  <table class="table table-sm table-bordered">
                                    <tr>
                                      <th class='text-right'>Sub Total: </th>
                                      <td  id='stotal'> 0 </td>
                                    </tr>
                                    <tr>
                                      <th class='text-right'>Discount on all: </th>
                                      <td id='disc_disp'> 0 </td>
                                    </tr>
                                    <tr>
                                      <th class='text-right'>Grand total: </th>
                                      <td id='gtotal'> 0 </td>
                                    </tr>
                                  </table>

                                                           
                            </div>

                
                              <legend class="w-auto text-primary border p-1  border-primary col-md-12"> Payment Information</legend>
                              
                              <div class="form-group col-md-4">
                                <label for="">Payment method</label>
                                <div class="input-group input-group-alt">
                                  <select name="pmeth" id="bss4" class="form-control pmeth" data-toggle='selectpicker' required data-live-search='true' >
                                    <option data-tokens='' value=''>Select payment method  </option>
                                    <?php 
                                      $stmt = "SELECT * FROM account";
                                      $result = mysqli_query($con, $stmt);
                                      while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['account_id'];
                                        $name = $row['account_name'];
                                        $num = $row['account_number'];
                                        echo "
                                          <option data-tokens='$num' value='$id'> $name - $num  </option>
                                        
                                        ";
                                      }
                                    
                                    ?>
                                  </select>
                                  <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button" title='Add new payment method'><i class="fa fa-plus"></i></button>
                                  </div>
                                </div>
                                <p id="pmMsg" class="text-danger d-none"></p>
                              </div> 
                              
                              <div class="form-group col-md-4">
                                <label for="">Amount</label>
                                <div class="input-group rounded-0">
                                  <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='paid_amount' id='paid_amount'  placeholder='Enter paying amount' step="0.01" required class="form-control rounded-0" id="pi9">
                                </div>
                              </div> 

                              <div class="form-group col-md-4">
                                <label for="">Balance</label>
                                <div class="input-group rounded-0">
                                  <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='balance' id='balance' placeholder='0.00' step="0.01" required class="form-control rounded-0" id="pi9" readonly>
                                </div>
                              </div> 

                              <div class="form-group col-md-12 text-center">
                                <center>
                                  <button class="btn btn-info btn-block col-md-3" id='btnSaveOrder'><i class="fa fa-save"></i> Save </button>
                                </center>
                              </div>
                         






                        </div>
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->


              <!-- Edit customer drawer -->
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
        position: 'top-center',
        showConfirmButton: false,
        timer: 3000
  });

    // Initialize session cart
    loadSessionCart();
    calculateTotals();

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
        // Trim any whitespace from response
        res = res.trim();
        console.log('Customer creation response:', res);
        
        if(res == 'found_email'){
          toastr.error("This email account is allready with another customer, try another one")
          $('.overlay').addClass('d-none')
          $('#cemail').addClass('is-invalid')
        }else if (res == "phone_found"){
          $('.overlay').addClass('d-none')
          toastr.error("This phone number is allready registered with another customer, try another one ")
          $('#cphone').addClass('is-invalid')
        }else if (res.startsWith('database_error:')){
          $('.overlay').addClass('d-none')
          toastr.error("Database error: " + res.replace('database_error: ', ''))
        }else if (res == 'success'){
          toastr.success("Customer added successfully and selected for this sale")
          $('#addCustModal').modal('hide');
          
          $('#frmCust')[0].reset();
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')

          // Reload customer dropdown and select the new customer
          $.ajax({
            url: '../jquery/live-cust.php',
            type: 'POST',
            data: {get_customers: true},
            dataType: 'json',
            success: function(response) {
              console.log('Customer dropdown response:', response);
              if (response && response.data) {
                $('#cust').empty();
                $('#cust').append('<option value="">Select Customer</option>');
                
                // Add all customers to dropdown
                response.data.forEach(function(customer) {
                  $('#cust').append('<option value="' + customer.customer_id + '">' + customer.cust_name + ' - ' + customer.cust_phone + '</option>');
                });
                
                // Select the newly created customer (last one in the list)
                var newCustomerId = response.data[response.data.length - 1].customer_id;
                console.log('Selecting new customer ID:', newCustomerId);
                $('#cust').val(newCustomerId);
                
                // Refresh selectpicker if it's being used
                if($('#cust').hasClass('selectpicker') || $('#cust').data('toggle') === 'selectpicker') {
                  $('#cust').selectpicker('refresh');
                }
                
                // Trigger change event to update customer details
                $('#cust').trigger('change');
                
                console.log('Customer dropdown updated successfully');
              } else {
                console.log('No customer data received');
              }
            },
            error: function(xhr, status, error) {
              console.log('Error loading customers:', error);
              console.log('Response:', xhr.responseText);
            }
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





  // get_invoice_num()
  // function get_invoice_num(){
  //   $.ajax({
  //       url:'../jquery/sale.php',
  //       type:'post',
  //       data: {get_invoice_num: true},
  //       success: function(data){
  //           $('#inum').val(data)
  //           //$('.overlay').addClass('d-none');

  //       }
  //   });
  // }

/// Searching items
  $(document).on('keyup', '#item_search', function(){
    var val = $(this).val();
    $('.sear_pro_res').removeClass('d-none');
    $('.sear_pro_res').html('')

    if(val.length > 1){
      $.ajax({
          url:"../jquery/sale.php",
          method:"POST",
          data:{product_sear:val},
          success:function(data){
              
              $('.sear_pro_res').html(data);
              
          }
      })

    }
  });

  // Clicking item on search result - Add to session cart
  $(document).on('click','.gsearch' ,function(){
        var $id = $(this).data('id')
        console.log('Adding item ID:', $id, 'to session cart');

        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{addToSessionCart:$id},
            success:function(res){
                // Trim any whitespace from response
                res = res.trim();
                console.log('Add to cart response:', res);
                
                if(res === 'success'){
                  var audio = document.getElementById("audio");
                    audio.play();
                    $('.sear_pro_res').addClass('d-none');
                    $('#item_search').val('')
                    loadSessionCart()
                    calculateTotals()
                    toastr.success("Item added to cart");
                } else if(res === 'item_not_found') {
                    toastr.error("Item not found in database");
                } else if(res === 'quantity_exceeded') {
                    toastr.error("Product quantity exceeded");
                } else {
                    console.log('Unexpected response:', res);
                    toastr.error("Failed to add item: " + res);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
                toastr.error("Network error. Please try again.");
            }
        })
    })

    // Quantity on blur - Update session cart
    $(document).on('blur', '#qty', function(){
        var id = $(this).data('id')
        var qty = $(this).closest("#qty").val()
        qty = Number(qty)

        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{updateSessionCartQty:id, qty:qty},
            success:function(res){
                if(res == 'success'){
                    loadSessionCart();
                    calculateTotals();
                } else if(res == 'quantity_exceeded'){
                    toastr.error("Product quantity exceeded");
                    loadSessionCart();
                    calculateTotals();
                } else {
                    console.log('Quantity update error:', res);
                    toastr.error("Failed to update quantity");
                }
            }
        })
    });

  

  // remove items from session cart
  $(document).on('click', '#rm_pro', function(){
    var id = $(this).data('id')
    console.log('Removing item ID:', id, 'from session cart');

    $.ajax({
        url:"../jquery/session-cart.php",
        method:"POST",
        data:{removeFromSessionCart:id},
        success:function(res){
            if(res == 'success'){
                loadSessionCart();
                calculateTotals();
                toastr.success("Item removed from cart");
            } else {
                console.log('Remove error:', res);
                toastr.error("Failed to remove item");
            }
        }
    })
  });

  // Quantity incrementing 
  $(document).on('click', '#btnPlus', function(){
        var oid = $(this).data('id')
        console.log('Incrementing item ID:', oid);
        
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{incrementSessionCart:oid},
            success:function(res){
                if(res == 'success'){
                    loadSessionCart();
                    calculateTotals();
                } else if(res == 'quantity_exceeded'){
                    toastr.error("Product quantity exceeded");
                    loadSessionCart();
                    calculateTotals();
                } else {
                    console.log('Increment error:', res);
                    toastr.error("Failed to increment quantity");
                }
            }
        })
  });

  // Quantity decrementing  
  $(document).on('click', '#btnMinus', function(){
        var oid = $(this).data('id')
        console.log('Decrementing item ID:', oid);
        
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{decrementSessionCart:oid},
            success:function(res){
                if(res == 'success'){
                    loadSessionCart();
                    calculateTotals();
                } else {
                    console.log('Decrement error:', res);
                    toastr.error("Failed to decrement quantity");
                }
            }
        })
    });


  //Discount on item - Update session cart
  $(document).on('blur', '#disc', function(){
        var id = $(this).data('id')
        var val = $(this).val()
  
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{updateSessionCartDiscount:id, discount:val},
            success:function(res){
                if(res == 'success'){
                    loadSessionCart();
                    calculateTotals();
                } else {
                    console.log('Discount update error:', res);
                    toastr.error("Failed to update discount");
                }
            }
        })
  });

  // DIscount on all
  $('#disc_total').on('change', function(){
        var data = $(this).val()
        if(data == ""){

        }else{
            $('#disc_disp').html(data+" $")
            grand_total()
            total();
        }

   });


   function calculateBalance(){
    var grandTotal = parseFloat($('#gtotal').text().replace('$', '').trim()) || 0;
    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
    var balance = grandTotal - paidAmount;
    
    // If paid amount is greater than grand total, show 0 balance (no negative balance)
    if(balance < 0) {
      balance = 0;
    }
    
    $('#balance').val(balance.toFixed(2));
   }

   // Calculate balance when amount paid changes
   $(document).on('input', '#paid_amount', function(){
    calculateBalance();
   });

   // Also recalculate balance when totals change
   $(document).on('blur', '#paid_amount', function(){
    calculateBalance();
   });

  

   $(document).on('blur', '#price', function(){
    var price = $(this).val();
    var id = $(this).data('id');

        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{updateSessionCartPrice: price, itemId: id},
            success:function(res){
                if(res == 'success'){
                    loadSessionCart();
                    calculateTotals();
                } else if (res == "price_too_low"){
                    toastr.error("Sale price cannot be lower than purchase price");
                } else {
                    console.log('Price update error:', res);
                    toastr.error("Failed to update price");
                }
            }
        })
   });

    // Load session cart
    function loadSessionCart(){
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{loadSessionCart:true},
            success:function(data){
                $('#order_list').html(data);
            }
        })
    }

    // Calculate totals from session cart
    function calculateTotals(){
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{calculateSessionTotals:true},
            success:function(data){
                var totals = JSON.parse(data);
                $('#stotal').html(totals.subtotal);
                $('#disc_disp').html(totals.discount);
                $('#gtotal').html(totals.grandtotal);
                
                // Recalculate balance when totals change
                calculateBalance();
            }
        })
    }


  // Update discount on all items
  $('#disc_total').on('change', function(){
        var discount = $(this).val();
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{updateSessionCartDiscountAll:discount},
            success:function(res){
                if(res == 'success'){
                    calculateTotals();
                }
            }
        })
   });

 

   // Saving Order - Commit session cart to database
   $(document).on('click', '#btnSaveOrder', function(){
      var cust = $('#cust').val();
      var sadate = $('.sdate').val();
      var status = $('#status').val();
      var pay_meth = $('#bss4').val();
      var amount = $('#paid_amount').val()
      var balance = $('#balance').val()
      var discount = $('#disc_total').val()
      var pdateline = $('#pdateline').val()
      var porder = $('#porder').val()

      $('.overlay').removeClass('d-none')

     // console.log(pdateline)
      if(cust == ""){
        $('#custMsg').removeClass('d-none');
        $('#custMsg').html('Please select customer');
        $('.customer').addClass('is-invalid')
        $('.overlay').addClass('d-none')
      }

      if(sadate == ""){
        $('#sdateMsg').removeClass('d-none');
        $('#sdateMsg').html('Please select sale date');
        $('#sdate').addClass('is-invalid')
        $('.overlay').addClass('d-none')
      }

      if(status == ""){
        $('#statusMsg').removeClass('d-none');
        $('#statusMsg').html('Please select order status');
        $('#status').addClass('is-invalid')
      $('.overlay').addClass('d-none')

      }

      // if(pay_meth == ""){
      //   $('#pmMsg').removeClass('d-none');
      //   $('#pmMsg').html('Please select  status');
      //   $('#bss4').addClass('is-invalid')
      // }

  
      if(cust == "" || sadate == "" || status == "" ){
        toastr.error("Some required fields are missing")
        $('.overlay').addClass('d-none')
      }else{
        // Commit session cart to database
        $.ajax({
            url:"../jquery/session-cart.php",
            method:"POST",
            data:{
                commitSessionCart: true,
                customer: cust,
                sale_date: sadate,
                status: status,
                payment_method: pay_meth,
                paid_amount: amount,
                discount_total: discount,
                due_date: pdateline,
                po_number: porder
            },
            success:function(res){
                // Trim whitespace from response
                res = res.trim();
                console.log('Save order response:', res);
                
                if(res == "success"){
                    toastr.success("Sale completed successfully");
                    
                    // Clear the cart display
                    $('#order_list').html('<tr><td colspan="6"><p class="text-center"><strong>No item added on the list</strong></p></td></tr>');
                    
                    // Reset totals
                    $('#stotal').html('0');
                    $('#disc_disp').html('0');
                    $('#gtotal').html('0');
                    
                    // Reset form fields
                    $('#frmAddCust')[0].reset();
                    $('#cust').val('').trigger('change');
                    $('#paid_amount').val('');
                    $('#balance').val('');
                    $('#disc_total').val('0');
                    
                    // Refresh selectpicker
                    if($('.selectpicker').length) {
                        $('.selectpicker').selectpicker('refresh');
                    }
                    
                    $('.overlay').addClass('d-none');
                    
                    // Redirect after 1 second
                    setTimeout(function(){
                        window.location.replace("<?= BASE_URL ?>sales/history");
                    }, 1000);
                } else {
                    toastr.error("Failed to complete sale: " + res);
                    $('.overlay').addClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.log('Save order error:', error);
                toastr.error("Network error saving order");
                $('.overlay').addClass('d-none');
            }
        })
      }

 

   });

   // Valdations [-Customer-]
   $('#bss3').on('change', function(){
     var val  = $(this).val()
     if(val != ""){
        $('#custMsg').addClass('d-none');
        $('#bss3').removeClass('is-invalid')
     }
   })

   $('.sdate').on('change', function(){
     var val  = $(this).val()
     if(val != "" ){
        $('#sdateMsg').addClass('d-none');
        $('.sdate').removeClass('is-invalid')
     }
   })

   $('#status').on('change', function(){
     var val  = $(this).val()
     if(val != ""){
        $('#statusMsg').addClass('d-none');
        $('#status').removeClass('is-invalid')
     }
   })

   $('.pmeth').on('change', function(){
     var val  = $(this).val()
     if(val != ""){
        $('#pmMsg').addClass('d-none');
        $('.pmeth').removeClass('is-invalid')
     }
   })

   // Customer selection change handler
   $('#cust').on('change', function(){
     var customerId = $(this).val();
     if(customerId){
       // Get customer details and update form fields
       $.ajax({
         url: '../jquery/customer.php',
         type: 'POST',
         data: {get_customer: customerId},
         dataType: 'json',
         success: function(response) {
           if(response && response.customer_id) {
             // Update customer details in form if needed
             console.log('Customer selected:', response.cust_name);
           }
         },
         error: function() {
           console.log('Error loading customer details');
         }
       });
     }
   })

})

</script>
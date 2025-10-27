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

<?php $title = "New Purchase"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Iibsiga'  ?>
      
      <?php $smenu = 'Iibsasho cusub'  ?>
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
                  <h1 class="page-title"> <a href="<?= BASE_URL ?>purchase/history" class='btn'> <i class="fa fa-arrow-left"></i> </a> New Purchase </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>purchase/history" class="btn btn-success text-light"> <i class="fa fa-list"></i> Purchase list </a>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">

                      
                      <div class="card-body ">
                        
                        <form class='row' method='post' id='frmAddCust'>

                          <!-- <div class='form-group col-md-2'>
                            <label for=''> Invoice number</label>
                            <input type='text' name='inum' id='inum' class='form-control rounded-0' required readonly value='SL-008'>
                          </div> -->

                          <div class='form-group col-md-6'>
                            <label for=''> Supplier *</label>
                            <div class="input-group input-group-alt">
                            <input type="hidden" name="order_num" id="order_num">

                              <select name="customer" id="bss3" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >
                                <option data-tokens='' value=''>Select supplier  </option>
                                
                                <?php
                                  $stmt = "SELECT * FROM supplier ORDER BY sup_name ASC";
                                  $result = mysqli_query($con, $stmt);
                                  while($row = mysqli_fetch_assoc($result)){
                                    $id = $row['supp_id'];
                                    $name = $row['sup_name'];
                                    $phone = $row['phone_num'];
                                    echo "
                                      <option data-tokens='$phone' value='$id'>$name  </option>

                                    ";
                                  }
                                ?>
                              </select>
                              <!-- <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button" data-toggle='modal' data-target='#addCustModal'><i class="fa fa-plus"></i></button>
                              </div> -->
                            </div>
                            <p class='text-danger d-none' id='custMsg'></p>
                           
                          </div>

                          <div class='form-group col-md-3'>
                            <label for=''> Purchase date *</label>
                            <input id="flatpickr03" type="hidden" placeholder="Select purchase date" name='sdate' id='sdate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                            <p class='text-danger d-none' id='sdateMsg'></p>
                          </div>

                          <div class="form-group col-md-3">
                            <label for="">Order Status</label>
                            <select name="status" id="status" class="form-control">
                              <option value="">Select order status</option>
                              <option value="Recieved">Recieved</option>
                              <option value="Pending">Pending</option>
                              <option value="Ordered">Ordered</option>
                            </select>
                            <p class='text-danger d-none' id='statusMsg'></p>

                          </div>

                          <div class="col-md-12">
                            <hr>
                          </div>

                          <div class="form-group col-md-12">
                            
                            <input type="search" name="item_search" id="item_search" autocomplete='off' class="form-control rounded-0  ui-autocomplete-input" placeholder='Select item'>
                            <ul class="list-group sear_pro_res d-none">
   
                            </ul>
                          </div>

                          <table class='table table-striped  my-3'>
                            
                              <thead>
                                  <tr class='bg-info text-light'>
                                      <th width="250">Item name</th>
                                      <th>Quantity</th>
                                      <th>Unit price</th>
                                      <th>Discount</th>
                                      <th>Total</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody id='order_list'>
                                  <tr>
                                      <td colspan='6'> <p class="text-center"><strong>No item added on the list</strong></p> </td>
                                  </tr>

                              </tbody>
                              <tfoot>
                                  <tr class='bg-info text-light'>
                                      <td colspan='4' class=' text-right'> <strong>Sub Total</strong> </td>
                                      <td colspan='2' class=''>   <strong id='totalamount'>0.00 $ </strong> </td>
                                  </tr>
                              </tfoot>
                            </table>
                        </form>
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
                              <div class="row">
                                <div class="col-md-8">
                                  <h5>Sub total: </h5>
                                  <h5>Discount on all: </h5>
                                  <h5>Grand Total: </h5>
                                </div>
                                <div class="col-md-4 text-left">
                                  <h5><span class='mx-3' id='stotal'>0 $</span></h5>
                                  <h5><span class='mx-3' id='disc_disp'>0 $</span></h5>
                                  <h5><span class='mx-3' id='gtotal' >0 $</span></h5>
                                </div>
                              </div>
                              
                            </div>

                          <div class="alert alert-primary col-md-12"> Payment Information </div>
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
                              <!-- <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button"><i class="fa fa-plus"></i></button>
                              </div> -->
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

                          <div class="form-group col-lg-12 text-center">
                           <center>
                           <button class="btn btn-info btn-block col-lg-3 text-center" id='btnSaveOrder'><i class="fa fa-save"></i> Save </button>
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

                              <div class='form-group col-md-6'>
                                <label for=''> Email Address</label>
                                <input type='text' name='cemail' id='cemail' class='form-control rounded-0'>
                              </div>

                              <div class='form-group col-md-6'>
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

  //Automatically inserting order in db
    $.ajax({
        url:"../jquery/purchase.php",
        method:"POST",
        data:{make_order:true},
        success:function(res){
            $('#order_num').val(res)
            console.log(res);
        }
    })

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
          toastr.success("Customer registered successfully")
          var custphone = $('#cphone').val();
          $('#frmCust')[0].reset();
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')
          $('#addCustModal').modal('hide');

          //Get updated customers 
          $.ajax({
              url:'../jquery/purchase.php',
              type:'post',
              data: {get_customer: custphone},
              success: function(data){
                 $('#bss3').append(data)
                  //$('.overlay').addClass('d-none');
                  

              }
          });

        }
      }
    })
  })

  get_invoice_num()
  function get_invoice_num(){
    $.ajax({
        url:'../jquery/purchase.php',
        type:'post',
        data: {get_invoice_num: true},
        success: function(data){
            $('#inum').val(data)
            //$('.overlay').addClass('d-none');

        }
    });
  }

/// Searching items
  $(document).on('keyup', '#item_search', function(){
    var val = $(this).val();
    $('.sear_pro_res').removeClass('d-none');
    $('.sear_pro_res').html('')

    if(val.length > 1){
      $.ajax({
          url:"../jquery/purchase.php",
          method:"POST",
          data:{product_sear:val},
          success:function(data){
              
              $('.sear_pro_res').html(data);
              
          }
      })

    }
  });

  // Clicking item on search result - Add to purchase session cart
  $(document).on('click','.gsearch' ,function(){
        var id = $(this).data('id')
        console.log('Adding item ID:', id, 'to purchase session cart');
        
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{addToPurchaseCart:id},
            success:function(res){
                // Trim any whitespace from response
                res = res.trim();
                console.log('Add to purchase cart response:', res);
                
                if(res === 'success'){
                    $('.sear_pro_res').addClass('d-none');
                    $('#item_search').val('')
                    loadPurchaseCart()
                    calculatePurchaseTotals()
                    toastr.success("Item added to purchase cart");
                } else if(res === 'item_not_found') {
                    toastr.error("Item not found in database");
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

    // Quantity on blur - Update purchase session cart
    $(document).on('blur', '#qty', function(){
        var id = $(this).data('id')
        var qty = $(this).closest("#qty").val()
        qty = Number(qty)

        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{updatePurchaseCartQty:id, qty:qty},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    loadPurchaseCart();
                    calculatePurchaseTotals();
                } else {
                    console.log('Quantity update error:', res);
                    toastr.error("Failed to update quantity");
                }
            }
        })
    });

  

  // remove items from purchase session cart
  $(document).on('click', '#rm_pro', function(){
    var id = $(this).data('id')
    console.log('Removing item ID:', id, 'from purchase session cart');

    $.ajax({
        url:"../jquery/purchase-session-cart.php",
        method:"POST",
        data:{removeFromPurchaseCart:id},
        success:function(res){
            res = res.trim();
            if(res === 'success'){
                loadPurchaseCart();
                calculatePurchaseTotals();
                toastr.success("Item removed from purchase cart");
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
        console.log('Incrementing purchase item ID:', oid);
        
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{incrementPurchaseCart:oid},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    loadPurchaseCart();
                    calculatePurchaseTotals();
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
        console.log('Decrementing purchase item ID:', oid);
        
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{decrementPurchaseCart:oid},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    loadPurchaseCart();
                    calculatePurchaseTotals();
                } else {
                    console.log('Decrement error:', res);
                    toastr.error("Failed to decrement quantity");
                }
            }
        })
    });


  //Discount on item - Update purchase session cart
  $(document).on('blur', '#disc', function(){
        var id = $(this).data('id')
        var val = $(this).val()
  
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{updatePurchaseCartDiscount:id, discount:val},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    loadPurchaseCart();
                    calculatePurchaseTotals();
                } else {
                    console.log('Discount update error:', res);
                    toastr.error("Failed to update discount");
                }
            }
        })
  });

  // Price change handler - Update purchase session cart
  $(document).on('blur', '#price', function(){
        var id = $(this).data('id')
        var price = $(this).val()
  
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{updatePurchaseCartPrice:price, itemId:id},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    loadPurchaseCart();
                    calculatePurchaseTotals();
                } else {
                    console.log('Price update error:', res);
                    toastr.error("Failed to update price");
                }
            }
        })
  });

  // Discount on all
  $('#disc_total').on('change', function(){
        var discount = parseFloat($(this).val()) || 0;
        
        // Update discount in session cart
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            data:{updateGlobalDiscount: discount},
            success:function(res){
                res = res.trim();
                if(res === 'success'){
                    calculatePurchaseTotals();
                    toastr.success("Discount updated");
                } else {
                    console.log('Discount update error:', res);
                    toastr.error("Failed to update discount");
                }
            }
        });
   });




   // Saving Order
   $(document).on('click', '#btnSaveOrder', function(){
      var supplier = $('#bss3').val();
      var purchase_date = $('.sdate').val();
      var status = $('#status').val();
      var pay_meth = $('#bss4').val();
      var amount = $('#paid_amount').val();
      var balance = $('#balance').val();
      var discount = $('#disc_total').val();
      var due_date = $('#due_date').val();
      var po_number = $('#po_number').val();

      if(supplier == ""){
        $('#custMsg').removeClass('d-none');
        $('#custMsg').html('Please select supplier');
        $('.customer').addClass('is-invalid')
      }

      if(purchase_date == ""){
        $('#sdateMsg').removeClass('d-none');
        $('#sdateMsg').html('Please select purchase date');
        $('#sdate').addClass('is-invalid')
      }

      if(status == ""){
        $('#statusMsg').removeClass('d-none');
        $('#statusMsg').html('Please select order status');
        $('#status').addClass('is-invalid')
      }

      // if(pay_meth == ""){
      //   $('#pmMsg').removeClass('d-none');
      //   $('#pmMsg').html('Please select  status');
      //   $('#bss4').addClass('is-invalid')
      // }

  
      if(supplier == "" || purchase_date == "" || status == "" ){
        toastr.error("Some required fields are missing")
      }else{
        // Check if cart is empty
        // Commit session cart to database (backend will validate if cart is empty)
        $.ajax({
            url:"../jquery/purchase-session-cart.php",
            method:"POST",
            dataType: 'json',
            data:{
                commitPurchaseCart: true,
                supplier: supplier,
                purchase_date: purchase_date,
                status: status,
                payment_method: pay_meth,
                paid_amount: amount,
                discount_total: discount,
                due_date: due_date,
                po_number: po_number
            },
            success:function(res){
                console.log('Commit response:', res);
                
                if(typeof res === 'object'){
                    if(res.success === true){
                        toastr.success("Purchase order created successfully");
                        
                        // Clear form and cart display
                        $('#frmAddCust')[0].reset();
                        $('#order_list').html('<tr><td colspan="6"><p class="text-center"><strong>No item added on the list</strong></p></td></tr>');
                        $('#totalamount').html('0.00 $');
                        $('#stotal').html('0 $');
                        $('#disc_disp').html('0 $');
                        $('#gtotal').html('0 $');
                        $('#disc_total').val('0');
                        $('#paid_amount').val('');
                        $('#balance').val('');
                        
                        // Redirect after 1 second
                        setTimeout(function(){
                            window.location.replace("<?= BASE_URL ?>purchase/history");
                        }, 1000);
                    } else if(res.error === 'missing_fields'){
                        toastr.error("Please fill in all required fields");
                    } else if(res.error === 'empty_cart'){
                        toastr.error("Please add items to the purchase order");
                    } else if(res.error === 'order_creation_failed'){
                        toastr.error("Failed to create purchase order: " + (res.message || ''));
                        console.error('Database error:', res.message);
                    } else if(res.error === 'item_insert_failed'){
                        toastr.error("Failed to add items to purchase: " + (res.message || ''));
                        console.error('Database error:', res.message);
                    } else if(res.error === 'order_update_failed'){
                        toastr.error("Failed to update purchase totals: " + (res.message || ''));
                        console.error('Database error:', res.message);
                    } else {
                        toastr.error("Failed to create purchase: " + (res.error || 'Unknown error'));
                        console.error('Error details:', res);
                    }
                } else {
                    // Handle legacy string responses
                    if(res === 'success'){
                        toastr.success("Purchase order created successfully");
                        setTimeout(function(){
                            window.location.replace("<?= BASE_URL ?>purchase/history");
                        }, 1000);
                    } else {
                        toastr.error("Failed to create purchase: " + res);
                    }
                }
            },
            error: function(xhr, status, error){
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                toastr.error("Network error. Please try again.");
            }
        });
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

   // Session Cart Functions
   function loadPurchaseCart(){
       $.ajax({
           url:"../jquery/purchase-session-cart.php",
           method:"POST",
           data:{loadPurchaseCart:true},
           success:function(data){
               $('#order_list').html(data);
           }
       })
   }

   function calculatePurchaseTotals(){
       $.ajax({
           url:"../jquery/purchase-session-cart.php",
           method:"POST",
           data:{calculatePurchaseTotals:true},
           dataType: 'json',
           success:function(res){
               console.log('Purchase totals:', res);
               $('#totalamount').html(res.subtotal + ' $');
               $('#stotal').html(res.subtotal + ' $');
               $('#disc_disp').html(res.discount + ' $');
               $('#gtotal').html(res.grandtotal + ' $');
               
               // Update balance when totals change
               calculateBalance();
           },
           error: function(xhr, status, error){
               console.log('Totals calculation error:', error);
               toastr.error('Failed to calculate totals');
           }
       })
   }
   
   // Calculate balance (Grand Total - Amount Paid)
   function calculateBalance(){
       var grandTotalText = $('#gtotal').text().replace('$', '').trim();
       var grandTotal = parseFloat(grandTotalText) || 0;
       var paidAmount = parseFloat($('#paid_amount').val()) || 0;
       var balance = grandTotal - paidAmount;
       
       // Don't show negative balance
       if(balance < 0) {
           balance = 0;
       }
       
       $('#balance').val(balance.toFixed(2));
   }
   
   // Calculate balance when amount paid changes
   $(document).on('input', '#paid_amount', function(){
       calculateBalance();
   });
   
   $(document).on('blur', '#paid_amount', function(){
       var grandTotalText = $('#gtotal').text().replace('$', '').trim();
       var grandTotal = parseFloat(grandTotalText) || 0;
       var amount = parseFloat($(this).val()) || 0;

       if(amount > grandTotal){
           toastr.error("The maximum amount you can pay is: $" + grandTotal.toFixed(2))
           $(this).val(grandTotal.toFixed(2))
           calculateBalance();
       } else {
           calculateBalance();
       }
   });

   // Load cart and calculate totals on page load
   loadPurchaseCart();
   calculatePurchaseTotals();

})

</script>
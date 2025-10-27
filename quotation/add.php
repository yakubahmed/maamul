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

<?php $title = "Add new quotation"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Qiimayn'  ?>
      
      <?php $smenu = 'Ku dar qiimeyn'  ?>
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
                  <h1 class="page-title"> New Qoutation </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>quotation/list" class="btn btn-success text-light"> <i class="fa fa-list"></i> Qoutation list </a>
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
                            <input type='text' name='inum' id='inum' class='form-control rounded-0' required readonly value='QUO-001'>
                          </div> -->

                          <div class='form-group col-md-8'>
                            <label for=''> Customer *</label>
                            <input type="hidden" name="order_num" id="order_num">
                            <div class="input-group input-group-alt">
                              <select name="customer" id="bss3" class="form-control customer rounded-0" data-toggle='selectpicker' required data-live-search='true' >
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
                              <!-- <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button" data-toggle='modal' data-target='#addCustModal'><i class="fa fa-plus"></i></button>
                              </div> -->
                            </div>
                            <p class='text-danger d-none' id='custMsg'></p>
                           
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Quotation date *</label>
                            <input id="flatpickr03" type="hidden" placeholder="Select quotation date" name='qdate' id='qdate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                            <p class='text-danger d-none' id='sdateMsg'></p>
                          </div>

                

                     
                          <div class="card col-md-12">
                            <div class="card-body">
                              <div class="form-group col-md-12">
                            
                                <div class="input-group has-clearable input-group-lg rounded-0">
                                     <input type="search" autocomplete='off' name="item_search" id="item_search" class="form-control form-control-lg rounded-0  ui-autocomplete-input" placeholder='Search product / item'> <label class="input-group-append" for="ai1"><span class="input-group-text"><span class="fa fa-search"></span></span></label>
                                </div>
                                <ul class="list-group sear_pro_res d-none">
       
                                </ul>
                              </div>

        
                                  <table class='table table-bordered  my-3'>
                                    
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
                                      <!-- <tfoot>
                                          <tr class='bg-info text-light'>
                                              <td colspan='4' class=' text-right'> <strong>Sub Total</strong> </td>
                                              <td colspan='2' class=''>   <strong id='totalamount'>0.00 $ </strong> </td>
                                          </tr>
                                      </tfoot> -->
                                    </table>
                              
                            </div>
                          </div>

                        </form>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                              <table class="table-bordered col-md-12 ">
                                <tr>
                                  <th>
                                    <div class="form-group col-md-12 ">
                                      <label for="">Discount on all</label>
                                      <input type="number" name="disc_total" steps='0.2' id="disc_total" value='0' class="form-control">
                                    </div>
                                  </th>
                                </tr>
                              </table>
              

                            </div>

                            <div class="col-md-6 text-right">
                              <div class="row">
                                <div class="col-md-12">
                                  <table class="table table-sm table-bordered">


                                    <tr>
                                      <td>Sub total:</td>
                                      <th  id='stotal'>0</th>
                                    </tr>

                                    <tr>
                                      <td>Discount on all:</td>
                                      <th  id='disc_disp'>0</th>
                                    </tr>

                                    <tr>
                                      <td>Grand total:</td>
                                      <th  id='gtotal'>0</th>
                                    </tr>


                                  </table>
                                 
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
        url:"../jquery/qoutation.php",
        method:"POST",
        data:{make_order:true},
        success:function(res){
            res = res.trim();
            console.log('Order created with ID:', res); // Debug log
            $('#order_num').val(res)
            if(!res){
                toastr.error('Failed to create order. Please refresh the page.');
            }
        },
        error: function(xhr, status, error){
            console.log('Error creating order:', error);
            toastr.error('Failed to initialize order. Please refresh the page.');
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
              url:'../jquery/qoutation.php',
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
        url:'../jquery/qoutation.php',
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
    console.log('Searching for:', val); // Debug log
    
    if(val.length > 1){
      $('.sear_pro_res').removeClass('d-none');
      $.ajax({
          url:"../jquery/qoutation.php",
          method:"POST",
          data:{product_sear:val},
          success:function(data){
              console.log('Search results:', data); // Debug log
              $('.sear_pro_res').html(data);
          },
          error: function(xhr, status, error){
              console.log('Search error:', error); // Debug log
              toastr.error('Failed to search items');
          }
      })
    }else{
      $('.sear_pro_res').addClass('d-none');
      $('.sear_pro_res').html('');
    }
  });

  // Clicking item on search result
  $(document).on('click','.gsearch' ,function(){
        $id = $(this).data('id')
        $order = $('#order_num').val()
        console.log('Adding item ID:', $id, 'to order:', $order); // Debug log
        
        if(!$order){
            toastr.error('Order ID not found. Please refresh the page.');
            return;
        }
        
        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{addPro:$id, orderid:$order},
            success:function(res){
                res = res.trim();
                console.log('Add item response:', res); // Debug log
                
                if(res == 'success'){
                    $('.sear_pro_res').addClass('d-none');
                    $('#item_search').val('')
                    pro_on_cart()
                    total()
                    grand_total()
                    toastr.success('Item added successfully');
                }else if(res == 'session_expired'){
                    toastr.error('Your session has expired. Please login again.');
                    setTimeout(function(){
                        window.location.replace('<?= BASE_URL ?>login');
                    }, 2000);
                }else if(res == 'invalid_input'){
                    toastr.error('Invalid item or order ID');
                }else if(res == 'item_not_found'){
                    toastr.error('Item not found in inventory');
                }else{
                    console.log('Error adding item:', res);
                    toastr.error('Failed to add item: ' + res);
                }
            },
            error: function(xhr, status, error){
                console.log('AJAX Error:', error);
                toastr.error('Network error. Please try again.');
            }
        })
    })

    // Quantity on blur
    $(document).on('blur', '#qty', function(){
        var id = $(this).data('id')
        var qty = $(this).closest("#qty").val()

        qty = Number(qty)

     
            $.ajax({
                url:"../jquery/qoutation.php",
                method:"POST",
                data:{upd_qty:id, qty:qty},
                success:function(res){
                    if(res == 'success'){
                        pro_on_cart();
                        total();
                    } else if( res == 'quantity_exceeded' ){
                        toastr.error("Product quantity exceeded");
                        pro_on_cart();
                        total();
                       grand_total()
                    } else{
                        console.log(res)
                    }
                }
            } )

       
       
        
    });

  

  // remove items on order 
  $(document).on('click', '#rm_pro', function(){
    var id = $(this).data('id')
    $order = $('#order_num').val()

    $.ajax({
        url:"../jquery/qoutation.php",
        method:"POST",
        data:{remove_pro:id, orderid:$order},
        success:function(res){
            if(res == 'deleted'){
                pro_on_cart()
                total();
                grand_total()
            }
        }
    })
  });

  // Quantity incrementing 
  $(document).on('click', '#btnPlus', function(){
        var oid = $(this).data('id')
        console.log(oid)
        var value = $('#qty').val()
        value = Number(value)
        
        if (value >= 1){
            $.ajax({
                url:"../jquery/qoutation.php",
                method:"POST",
                data:{inc_qty:oid},
                success:function(res){
                    if(res == 'success'){
                        pro_on_cart();
                        total();
                       grand_total()
                    } else if( res == 'quantity_exceeded' ){
                        toastr.error("Product quantity exceeded");
                        pro_on_cart();
                        total();
                        grand_total()
                    }else{
                        console.log(res)
                    }
                }
            } )

        }
  });

  // Quantity decrementing  
  $(document).on('click', '#btnMinus', function(){
        var oid = $(this).data('id')
        console.log(oid)
        var value = $('#qty').val()
        value = Number(value)
        
        if (value <= 1){
            
        }else{
            $.ajax({
                url:"../jquery/qoutation.php",
                method:"POST",
                data:{oiid:oid},
                success:function(res){
                    if(res == 'success'){
                        pro_on_cart();
                        total();
                        grand_total()
                    }else{
                        console.log(res)
                    }
                }
            } )
        } 
    });


  //Discount on item
  $(document).on('blur', '#disc', function(){
        var id = $(this).data('id')
        var val = $(this).val()
  
        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{disc_upd:id, val_des:val},
            success:function(res){
                if(res == 'updated'){
                    pro_on_cart()
                    total();
                    grand_total()
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
        }

   });


   function balance(){
    var amount = $('#paid_amount').val()
    $order = $('#order_num').val()

        console.log(amount)
        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{paid_amount:amount,orderid:$order},
            success:function(res){
                $('#balance').val(res)  
                
                
            }
        })
   }

   $(document).on('blur', '#paid_amount', function(){
    $order = $('#order_num').val()
    $amount = $('#balance').val()

       var amount = $(this).val()
        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{paid_amount:amount, orderid:$order},
            success:function(res){
                $('#balance').val(res)  
                console.log(res)

                
                
            }
        })
   });

    //products on the cart 
    function pro_on_cart(){
      $order = $('#order_num').val()

        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{pro_on_cart:true,orderid:$order },
            success:function(data){
                $('#order_list').html(data);
            }
        })

    }

    //Display sum of the products odered
    function total(){
      $order = $('#order_num').val()

        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{total_pro:true, orderid:$order},
            success:function(data){
                $('#totalamount').html(data);
                $('#stotal').html(data)
                
            }
        })
    }


  //Grand Total
   function grand_total(){
    var   discount = $('#disc_total').val() 
    $order = $('#order_num').val()

    $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{grand_total:discount, orderid: $order},
            success:function(res){
                $('#gtotal').html(res )
                
                
            }
        })

        balance()
   }


   // Saving Order
   $(document).on('click', '#btnSaveOrder', function(){
      var cust = $('#bss3').val();
      var sadate = $('.sdate').val();
      var status = $('#status').val();
      var pay_meth = $('#bss4').val();
      var amount = $('#paid_amount').val()
      var balance = $('#balance').val()
      var discount = $('#disc_total').val()
      var pdateline = $('#pdateline').val()
      
      console.log('Save button clicked'); // Debug log
      
      if(cust == ""){
        $('#custMsg').removeClass('d-none');
        $('#custMsg').html('Please select customer');
        $('.customer').addClass('is-invalid')
      }

      if(sadate == ""){
        $('#sdateMsg').removeClass('d-none');
        $('#sdateMsg').html('Please select sale date');
        $('#sdate').addClass('is-invalid')
      }

      if(cust == "" || sadate == "" ){
        toastr.error("Some required fields are missing")
      }else{   
         $order = $('#order_num').val()
         
         if(!$order){
            toastr.error("Order ID not found. Please refresh the page.");
            return;
         }
         
         console.log('Saving order:', $order); // Debug log

        // Updating order information
        $.ajax({
            url:"../jquery/qoutation.php",
            method:"POST",
            data:{
                update_order_final:cust, 
                sdate: sadate, 
                discount_on_all:discount, 
                order_id: $order
            },
            beforeSend: function(){
                $('.overlay').removeClass('d-none');
            },
            success:function(res){
                res = res.trim();
                console.log('Save response:', res); // Debug log
                $('.overlay').addClass('d-none');
                
                if(res == "success"){
                    toastr.success("Quotation created successfully")
                    setTimeout(function(){
                        window.location.replace('<?= BASE_URL ?>quotation/list');
                    }, 1000);
                }else if(res == "session_expired"){
                    toastr.error("Your session has expired. Please login again.");
                    setTimeout(function(){
                        window.location.replace('<?= BASE_URL ?>login');
                    }, 2000);
                }else if(res == "missing_fields"){
                    toastr.error("Please fill in all required fields");
                }else if(res == "no_items"){
                    toastr.error("Please add at least one item to the quotation");
                }else{
                    console.log('Error details:', res); // Debug log
                    toastr.error("Failed to create quotation: " + res)
                }
            },
            error: function(xhr, status, error){
                console.log('AJAX Error:', error); // Debug log
                $('.overlay').addClass('d-none');
                toastr.error("Network error. Please try again.");
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




})

</script>
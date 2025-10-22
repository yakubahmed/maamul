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

<?php $title = "Edit Sale"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Sale'  ?>
      
      <?php $smenu = ''  ?>
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
            <?php if(isset($_GET['id'])){ ?>

              <?php
                $id = $_GET['id'];
                $stmt = "SELECT * FROM payment WHERE order_id  = $id";  
                $result = mysqli_query($con, $stmt);
                if(mysqli_num_rows($result) > 1){
                  echo "
                    <h6 class='alert alert-info'>
                      This sale is not editable, becuase this order linked to some payments.
                    </h6>
                  ";
                 
                }else{
                
              ?>

              <!-- .page-title-bar -->
              <header class="page-title-bar">
              <div class="row">
                <div class="col-md-6">
                  <h1 class="page-title"> <a href="<?= BASE_URL ?>sales/history" class="btn "> <i class="fa fa-arrow-left"></i> </a> Edit Sale </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="<?= BASE_URL ?>customer/list" class="btn btn-success text-light"> <i class="fa fa-plus"></i> POS </a> -->
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


                          <div class='form-group col-md-6'>
                            <label for=''> Customer *</label>
                            <div class="input-group input-group-alt">
                              <select name="customer" id="bss3" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >                                
                                <?php
                                  $cid = $_GET['id'];
                                  $stmt = "SELECT * FROM customer WHERE customer_id in (SELECT cust_id FROM orders WHERE order_id = $cid) LIMIT 1";
                                  $result = mysqli_query($con, $stmt);
                                  while($row = mysqli_fetch_assoc($result)){
                                    $id = $row['customer_id'];
                                    $name = $row['cust_name'];
                                    $phone = $row['cust_phone'];
                                    echo "
                                      <option data-tokens='$phone' value='$id'>$name  </option>

                                    ";
                                  }
                                                                  
                                  $stmt = "SELECT * FROM customer WHERE  customer_id NOT IN (SELECT cust_id FROM orders WHERE order_id = $cid)";
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

                          <div class='form-group col-md-3'>
                            <label for=''> Sale date *</label>
                            <?php 
                              $id = $_GET['id'];
                              $stmt = "SELECT order_date FROM orders WHERE order_id = $id";
                              $result = mysqli_query($con, $stmt); 
                              if($row = mysqli_fetch_assoc($result)){
                                $date = date("Y-m-d", strtotime($row['order_date']))  ;
                                echo "
                                <input id='flatpickr03' type='hidden' value='$date' placeholder='Select sale date' name='sdate' id='sdate' class='form-control flatpickr-input rounded-0 sdate' data-toggle='flatpickr' required data-alt-input='true'  data-alt-format='F j, Y' data-date-format='Y-m-d'>
                                
                                ";
                              }
                            ?>
                            <p class='text-danger d-none' id='sdateMsg'></p>
                          </div>

                          <div class="form-group col-md-3">
                            <label for="">Order Status</label>
                            <select name="status" id="status" class="form-control">
                              <?php 
                                $id = $_GET['id'];
                                $stmt = "SELECT order_status FROM orders WHERE order_id = $id";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                  $status = $row['order_status'];
                                  if($status == "Ordered"){
                                    $status = "
                                      <option value='Ordered'>Ordered</option>
                                      <option value='Confirmed'>Confirmed</option>
                                      <option value='Delivered'>Delivered</option>
                                    ";  
                                  }else if($status == "Confirmed"){
                                    $status = "
                                    <option value='Confirmed'>Confirmed</option>
                                    <option value='Ordered'>Ordered</option>
                                    <option value='Delivered'>Delivered</option>
                                  ";  
                                  }else if($status == "Delivered"){
                                    $status = "
                                    <option value='Delivered'>Delivered</option>
                                    <option value='Ordered'>Ordered</option>
                                    <option value='Confirmed'>Confirmed</option>
                                  ";  
                                  }

                                  echo $status;
                                }
                              
                              ?>
                              
                            </select>
                            <p class='text-danger d-none' id='statusMsg'></p>

                          </div>

                          <div class="col-md-12">
                            <hr>
                          </div>

                          <div class="alert alert-info col-md-12">
                            <i class="fa fa-info-circle mr-2"></i>
                            <strong>Note:</strong> The items below are from the original sale and cannot be modified. You can only update payment information and discount.
                          </div>

                          <table class='table table-striped  my-3'>
                            
                              <thead>
                                  <tr class='bg-info text-light'>
                                      <th width="250">Item name</th>
                                      <th>Quantity</th>
                                      <th>Unit price</th>
                                      <th>Discount</th>
                                      <th>Total</th>
                                  </tr>
                              </thead>
                              <tbody id='order_list'>
                                  <?php 
                                    $id = $_GET['id'];
                                       //Getting order id
                                    


                                      $stmt = "SELECT item_name, sale_price, order_item.qty,order_item.discount, order_item.sub_total, order_item_id  ";
                                      $stmt .= " FROM item, order_item WHERE item.item_id = order_item.item_id AND order_item.order_id = $id ";
                                      $result = mysqli_query($con, $stmt );
                                      if(mysqli_num_rows($result) > 0){
                                          while($row = mysqli_fetch_assoc($result)){
                                              $pname = $row['item_name'];
                                              $price = $row['sale_price'];
                                              $qty = $row['qty'];
                                              $dis = $row['discount'];
                                              $subtotal = $row['sub_total'];
                                              $oiid = $row['order_item_id'];
                                      
                                              echo "
                                              <tr>
                                                  <td>$pname</td>
                                                  <td class='text-center'>$qty</td>
                                                  <td class='text-right'>\$$price</td>
                                                  <td class='text-right'>\$$dis</td>
                                                  <td class='text-right'>\$$subtotal</td>
                                              </tr>
                                              ";
                                          }

                                      }else{
                                          echo "
                                          <tr>
                                              <td colspan='5'> <p class='text-center'><strong>No item added on the list</strong></p> </td>
                                          </tr>
                                          ";
                                      }
                                                                    
                                  
                                  ?>

                              </tbody>
                              <tfoot>
                                  <tr class='bg-info text-light'>
                                      <td colspan='4' class=' text-right'> <strong>Sub Total</strong> </td>
                                      <td class='text-right'>   <strong id='totalamount'>0.00 $ </strong> </td>
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
                                <?php 
                                  $oid = $_GET['id'];
                                  $stmt = "SELECT * FROM account WHERE account_id IN 
                                  (SELECT account FROM payment WHERE order_id = $oid)";
                                  $result = mysqli_query($con, $stmt);
                                  while($row = mysqli_fetch_assoc($result)){
                                    $id = $row['account_id'];
                                    $name = $row['account_name'];
                                    $num = $row['account_number'];
                                    echo "
                                      <option data-tokens='$num' value='$id'> $name - $num  </option>
                                    
                                    ";
                                  }

                                  $stmt = "SELECT * FROM account WHERE account_id NOT IN 
                                  (SELECT account FROM payment WHERE order_id = $oid)";
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
                              <?php
                              function getAmount(){
                                global $con;
                                $id = $_GET['id'];
                                $stmt = "SELECT * FROM orders WHERE order_id = $id";
                                $result = mysqli_query($con, $stmt);
                                if($row = mysqli_fetch_assoc($result)){
                                  return $row['amount'];
                                }
                                
                              }
                              ?>
                              <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button"><i class="fa fa-plus"></i></button>
                              </div>
                            </div>
                            <p id="pmMsg" class="text-danger d-none"></p>
                          </div> 

                          <div class="form-group col-md-4">
                            <label for="">Amount</label>
                            <div class="input-group rounded-0">
                              <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='paid_amount' id='paid_amount' value="<?= getAmount(); ?>" placeholder='Enter paying amount' step="0.01" required class="form-control rounded-0" id="pi9">
                            </div>
                          </div> 

                          <div class="form-group col-md-4">
                            <label for="">Balance</label>
                            <div class="input-group rounded-0">
                              <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='balance' id='balance' placeholder='0.00' step="0.01" required class="form-control rounded-0" id="pi9" readonly>
                            </div>
                          </div> 

                          <div class="form-group col-md-3">
                            <button class="btn btn-info btn-block" id='btnSaveOrder'><i class="fa fa-save"></i> Save changes </button>
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
              <?php } }else{ ?>
                    <!-- .empty-state -->
                    <main id="notfound-state" class="empty-state empty-state-fullpage bg-black">
                    <!-- .empty-state-container -->
                    <div class="empty-state-container">
                        <div class="card">
                        <div class="card-header bg-light text-left">
                            <i class="fa fa-fw fa-circle text-red"></i> <i class="fa fa-fw fa-circle text-yellow"></i> <i class="fa fa-fw fa-circle text-teal"></i>
                        </div>
                        <div class="card-body">
                            <h1 class="state-header display-1 font-weight-bold">
                            <span>4</span> <i class="far fa-frown text-red"></i> <span>4</span>
                            </h1>
                            <h3> Page not found! </h3>
                            <p class="state-description lead"> Sorry, we've misplaced that URL or it's pointing to something that doesn't exist. </p>
                            <div class="state-action">
                            <a href="<?= BASE_URL ?>" class="btn btn-lg btn-light"><i class="fa fa-angle-right"></i> Go Home</a>
                            </div>
                        </div>
                        </div>
                    </div><!-- /.empty-state-container -->
                    </main><!-- /.empty-state -->
                <?php } ?>
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
    // $.ajax({
    //     url:"../jquery/sale.php",
    //     method:"POST",
    //     data:{make_order:true},
    //     success:function(res){
    //         $('#order_num').val(res)
    //         console.log(res);
    //     }
    // })

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
              url:'../jquery/sale.php',
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
        url:'../jquery/edit-sale.php',
        type:'post',
        data: {get_invoice_num: <?= $_GET['id'] ?>},
        success: function(data){
            $('#inum').val(data)
            //$('.overlay').addClass('d-none');
            console.log(data)

        }
    });
  }

  /// Item modification disabled - items are read-only
  // Item search and add functionality removed for professional edit page
  // Users can only update payment information and discount on all


  // All item modification functions removed - items are read-only in edit mode

  // DIscount on all
  $('#disc_total').on('change', function(){
        var data = $(this).val()
        if(data == ""){

        }else{
            $('#disc_disp').html(data+" $")
            grand_total()
        }

   });

   // Calculate balance on page load
   calculateBalance();
   
   function calculateBalance(){
    var grandTotal = parseFloat($('#gtotal').text().replace('$', '').trim()) || 0;
    var paidAmount = parseFloat($('#paid_amount').val()) || 0;
    var balance = grandTotal - paidAmount;
    
    // If paid amount is greater than grand total, show 0 balance
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
    var grandTotal = parseFloat($('#gtotal').text().replace('$', '').trim()) || 0;
    var amount = parseFloat($(this).val()) || 0;

    if(amount > grandTotal){
      toastr.error("The maximum amount you can pay is: $" + grandTotal.toFixed(2))
      $(this).val(grandTotal.toFixed(2))
      calculateBalance();
    } else {
      calculateBalance();
    }
   });

    //products on the cart 
    function pro_on_cart(){
      $order = <?= $_GET['id'] ?>

        $.ajax({
            url:"../jquery/edit-sale.php",
            method:"POST",
            data:{pro_on_cart:true,orderid:$order },
            success:function(data){
                $('#order_list').html(data);
            }
        })

    }

    //Display sum of the products odered
    total()
    function total(){
      //$order = $('#order_num').val()

        $.ajax({
            url:"../jquery/edit-sale.php",
            method:"POST",
            data:{total_pro:true, orderid:<?= $_GET['id'] ?>},
            success:function(data){
                $('#totalamount').html(data);
                $('#stotal').html(data)
                
                // Recalculate balance when total changes
                calculateBalance();
            }
        })
    }


  //Grand Total
  grand_total()
   function grand_total(){
    var   discount = $('#disc_total').val() 
    //$order = $('#order_num').val()

    $.ajax({
            url:"../jquery/sale.php",
            method:"POST",
            data:{grand_total:discount, orderid: <?= $_GET['id'] ?>},
            success:function(res){
                $('#gtotal').html(res )
                
                // Recalculate balance when grand total changes
                calculateBalance();
            }
        })
   }


   // Saving Order
   $(document).on('click', '#btnSaveOrder', function(){
      console.log('Save button clicked');
      
      var cust = $('#bss3').val();
      var sadate = $('.sdate').val();
      var status = $('#status').val();
      var pay_meth = $('#bss4').val();
      var amount = $('#paid_amount').val()
      var balance = $('#balance').val()
      var discount = $('#disc_total').val()

      console.log('Form values:', {cust, sadate, status, pay_meth, amount, balance, discount});

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

  
      if(cust == "" || sadate == "" || status == "" ){
        toastr.error("Some required fields are missing")
      }else{
        if(cust == 29 && amount == 0 ){
            if(pay_meth == ""){
                toastr.error("Please select payment method")
                $('#bss4').addClass('is-invalid')
                $('#pmMsg').html('Please payment method');
                $('#pmMsg').removeClass('d-none');


            }

            if (amount == 0){
                $("#paid_amount").addClass('is-invalid')
                toastr.error("Walking customer must make full payment")
                
            }
        }else{
            if(cust == 29){
              $order = $('#order_num').val()

                //Checking partial payments for walking cust
                $.ajax({
                    url:"../jquery/sale.php",
                    method:"POST",
                    data:{check_partial_py:amount, des_partial:discount, order_id:$order},
                    success:function(res){
                        if(res > amount){
                            toastr.error("Partial payments are not allowed for walking customer")
                        }else{
                                         // Updating order information
                          $.ajax({
                              url:"../jquery/sale.php",
                              method:"POST",
                              data:{
                                  update_order_final:cust, 
                                  sdate: sadate, 
                                  discount_on_all:discount, 
                                  ptype:pay_meth, 
                                  paid_amount_order:amount,
                                  order_id: <?= $_GET['id'] ?>, 
                                  status: status
                              },
                              success:function(res){
                                  res = res.trim();
                                  console.log('Update response:', res);
                                  if(res == "success"){
                                      toastr.success("Sale updated successfully")
                                      setTimeout(function(){
                                          window.location.replace("<?= BASE_URL ?>sales/history");
                                      }, 1000);
                                  }else{
                                    toastr.error("Failed to update sale: " + res)
                                    console.log('Error:', res)
                                  }
                              },
                              error: function(xhr, status, error){
                                  console.log('AJAX Error:', error);
                                  toastr.error("Network error. Please try again.");
                              }
                          })
                        }
                       
                    }
                })
            }else{
              $order = $('#order_num').val()

               // Updating order information
                $.ajax({
                    url:"../jquery/edit-sale.php",
                    method:"POST",
                    data:{
                        update_order_final:cust, 
                        sdate: sadate, 
                        discount_on_all:discount, 
                        ptype:pay_meth, 
                        paid_amount_order:amount,
                        order_id: <?= $_GET['id'] ?>, 
                        status: status
                    },
                    success:function(res){
                        res = res.trim();
                        console.log('Update response:', res)
                        if(res == "success"){
                            toastr.success("Sale updated successfully")
                            setTimeout(function(){
                                window.location.replace("<?= BASE_URL ?>sales/history");
                            }, 1000);
                        } else {
                            toastr.error("Failed to update sale: " + res)
                            console.log('Error:', res);
                        }
                    },
                    error: function(xhr, status, error){
                        console.log('AJAX Error:', error);
                        toastr.error("Network error. Please try again.");
                    }
                })
            }
        }
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
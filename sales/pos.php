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

/* Customer dropdown styling */
.bootstrap-select .dropdown-toggle {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.bootstrap-select .dropdown-toggle:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
/* POS cart UI polish */
.table.table-head-fixed th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 1;
}
.cart-price-input, .cart-qty-input {
    text-align: center;
}
.cart-actions .btn {
    line-height: 1;
}

/* Hide sidebar on POS page only */
aside.app-aside { display: none !important; }
.app-main { margin-left: 0 !important; }
</style>
<?php $title = 'POS'; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Sale'  ?>
      
      <?php $smenu = 'POS'  ?>
  <body class='app '>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php //$isactive = 'sales'; ?>
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->

        <?php $shiddent = 1; ?>
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
                  <h1 class="page-title"> <a href="<?= BASE_URL ?>" class="btn"> <i class="fa fa-arrow-left"></i> </a> <?= __t('POS') ?> <small><?= __t('Sale') ?></small> </h1>
                </div>
                <!-- <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>sales/history" class="btn btn-success text-light"> <i class="fa fa-list"></i> Sales history </a>
                </div> -->
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">

                      
                      <div class="card-body ">
                      <div class="row">
                            <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-body">
                                <div class="row" >
                                    <div class="form-group col-md-4 ">
                                    <select name="category" id="category" class='form-control' data-toggle='selectpicker'  data-live-search='true' >
                                        <option value=""><?= __t('Item category') ?></option>
                                        <?php 

                                          $stmt = "SELECT * FROM item_category ORDER BY category_name ASC";
                                          $result = mysqli_query($con, $stmt);
                                          while($row = mysqli_fetch_assoc($result)){
                                            $id = $row['itemcat_id'];
                                            $name = $row['category_name'];

                                            echo "
                                              <option value='$id' data-id='$id'>$name</option>
                                            ";
                                          }
                                        
                                        ?>
                                    </select>
                                    </div>
                                    <div class="form-group col-md-4 ">
                                    <input type="text" name="search_product" id="search_product"  data-kioskboard-type="keyboard" data-kioskboard-specialcharacters="false"  class="form-control virtual-keyboard" placeholder='<?= __t('Search product') ?>'>
                                    </div>
                                    <div class="form-group col-md-4 ">
                                    <input type="text" name="barcode_scanner" id="barcode_scanner" class="form-control" placeholder='<?= __t('Barcode Scanner') ?>' autocomplete="off">
                                    </div>
                                    <div class="form-group-col-md-12" style="width:100%;height:400px; position: relative; overflow: hidden;overflow-y: scroll;">
                                    
                                    <div class="products">
                                        <audio src="../assets/sound/add.mp4" controls class="d-none" id='sound'></audio>
                                        <div class="container">
                                        
                                          <div class="row pswp-gallery" data-pswp-uid="1">
                                           
                                            <?php
                                            
                                              $stmt = "SELECT * FROM item ORDER BY item_name ASC";
                                              $result = mysqli_query($con, $stmt); 
                                              while($row = mysqli_fetch_assoc($result)){
                                                $id = $row['item_id'];
                                                $name = $row['item_name'];
                                                $price = $row['sale_price'];
                                                $qty = $row['qty'];
                                                $img = $row['item_image'];

                                                if(empty($img)){ $img = 'item_placeholder.png'; }

                                                echo "
                                                    <div class='col-xl-3 col-lg-4 col-sm-6'>
                                                      <!-- .card -->
                                                      <div class='card card-figure'>
                                                        <!-- .card-figure -->
                                                        <figure class='figure'>
                                                          <!-- .figure-img -->
                                                          <div class='figure-img'>
                                                            <img class='img-fluid' src='../assets/images/products/$img' alt='Card image cap'> <a href='#' class='img-link' data-item-id='$id' data-item-name='$name' data-item-price='$price' data-item-qty='$qty'>
                                                              <div class='tile tile-circle bg-danger'>
                                                                <span class='fa fa-plus py-2'></span>
                                                              </div>
                                                            </a>
                                                          </div><!-- /.figure-img -->
                                                          <!-- .figure-caption -->
                                                          <figcaption class='figure-caption'>
                                                            <h6 class='figure-title'>
                                                              <a href='#'>$ $price </a>
                                                            </h6>
                                                            <p class='text-muted mb-0'> $name ($qty)</p>
                                                          </figcaption><!-- /.figure-caption -->
                                                        </figure><!-- /.card-figure -->
                                                      </div><!-- /.card -->
                                                    </div>
                                                
                                                
                                                ";
                                              }
                                            
                                            
                                            ?>

                  
          
                                          </div>
                                    


                                        </div>


                                    </div>
                                
                                    

                                    </div>

                                    
                                </div>
                    
                            </div>
                            
                            </div>
                            
                  </div>
                  <div class='col-md-4 '>
                      <div class="card bg-light">
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden;overflow-y: scroll; width: auto; height: 400px;">
                            <table class="table table-hover table-striped table-sm table-head-fixed " data-height='100' >
                                <thead>
                                    <tr class='' >
                                        <th><?= __t('Item') ?></th>
                                        <th><?= __t('Unit price') ?></th>
                                        <th><?= __t('Qty') ?></th>
                                        <th><?= __t('Amount') ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id='orderItems'>
                               
                                </tbody>
                                
                            </table>
                          </div>

                          <table class="table" style='border-radius:0;'>
                              <tr class='bg-dark text-light' style='border-radius:0;'>
                                <td colspan='3' style='border-radius:0;'> <?= __t('Total') ?></td>
                                <td class="text-right" id='Total' style='font-size:larger; border-radius:0;' > 0.00$</td>
                              </tr>
                          </table>
                          <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-info"id='payment'  data-toggle="modal" data-target="#OrderModal" style='border-radius:0;'><?= __t('Order now') ?></button>
                            <button type="button" class="btn btn-danger" id='resetOrder'  style='border-radius:0;'><?= __t('Reset') ?></button>
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
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong><?= __t('Add customer') ?></strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                            <form class='row' method='post' id='frmCust'>

                              <div class='form-group col-md-6'>
                                <label for=''> <?= __t('Customer name') ?> *</label>
                                <input type='text' name='cname' id='cname' class='form-control rounded-0' required>
                              </div>

                              <div class='form-group col-md-6'>
                                <label for=''> <?= __t('Phone number') ?> *</label>
                                <input type='text' name='cphone' id='cphone' class='form-control  rounded-0' required>
                              </div>

                              <div class='form-group col-md-6'>
                                <label for=''> <?= __t('Email Address') ?></label>
                                <input type='text' name='cemail' id='cemail' class='form-control rounded-0'>
                              </div>

                              <div class='form-group col-md-6'>
                                <label for=''> <?= __t('Status') ?> *</label>
                                <select name='status' id='status' class='form-control'>
                                  <option value=''> <?= __t('Select status') ?></option>
                                  <option value='Active'> <?= __t('Active') ?></option>
                                  <option value='Disabled'> <?= __t('Disabled') ?></option>
                                </select>
                              </div>

                              <div class='form-group col-md-12'>
                                <label for=''> <?= __t('Address') ?></label>
                                <textarea name='addr' id='addr' class='form-control rounded-0'></textarea>
                              </div>

                              <div class='form-group col-md-6'>
                                <button type='submit' class='btn btn-info rounded-0'><?= __t('Save customer') ?></button>
                              </div>
                            </form>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal"><?= __t('Close') ?></button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
             

              </div><!-- /.page-section -->

              <!-- Order Modal -->
              <div class="modal fade" id="OrderModal" tabindex="-1" role="dialog" aria-labelledby="OrderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="OrderModalLabel">Complete Order</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form id="orderForm">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="card bg-light">
                              <div class="card-body">
                                <h6 class="mb-3">Customer & Payment</h6>
                                <div class="form-group">
                                  <label for="customer_dropdown">Select Customer</label>
                                  <select class="form-control" id="customer_dropdown" data-toggle="selectpicker" data-live-search="true">
                                    <option value="">Select Customer</option>
                                    <?php 
                                      $stmt = "SELECT * FROM customer WHERE status = 'Active' ORDER BY cust_name ASC";
                                      $result = mysqli_query($con, $stmt);
                                      while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['customer_id'];
                                        $name = $row['cust_name'];
                                        $phone = $row['cust_phone'];
                                        echo "<option value='$id' data-phone='$phone' data-name='$name'>$name - $phone</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="payment_method"><?= __t('Payment Method') ?></label>
                                  <select class="form-control" id="payment_method" required>
                                    <option value=""><?= __t('Select Payment Method') ?></option>
                                    <?php 
                                      $stmt = "SELECT * FROM account ORDER BY account_name ASC";
                                      $result = mysqli_query($con, $stmt);
                                      while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['account_id'];
                                        $name = $row['account_name'];
                                        $num = $row['account_number'];
                                        echo "<option value='$id'>$name - $num</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group mb-0">
                                  <label for="order_notes"><?= __t('Order Notes') ?></label>
                                  <textarea class="form-control" id="order_notes" rows="4" placeholder="Any special notes..."></textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="card totals-card">
                              <div class="card-body">
                                <h6 class="mb-3"><?= __t('Totals') ?></h6>
                                <div class="form-group">
                                  <label class="mb-1"><?= __t('Subtotal') ?></label>
                                  <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" class="form-control" value="0.00" id="subtotal_readonly" readonly>
                                  </div>
                                  <small class="text-muted"><?= __t('Calculated from items') ?></small>
                                </div>
                                <div class="form-group">
                                  <label for="discount_all" class="mb-1"><?= __t('Discount (all)') ?></label>
                                  <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" class="form-control" id="discount_all" step="0.01" value="0">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="mb-1"><?= __t('Grand Total') ?></label>
                                  <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="text" class="form-control font-weight-bold" value="0.00" id="grandtotal_readonly" readonly>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="amount_paid" class="mb-1"><?= __t('Amount Paid') ?></label>
                                  <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" class="form-control" id="amount_paid" step="0.01" required>
                                  </div>
                                </div>
                                <div class="mb-2 d-none" id="change_alert">
                                  <span class="badge badge-warning p-2"><?= __t('Change') ?>: $<span id="change_amount">0.00</span></span>
                                </div>
                                <div class="d-none" id="balance_alert">
                                  <span class="badge badge-secondary p-2"><?= __t('Balance Due') ?>: $<span id="balance_amount">0.00</span></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __t('Cancel') ?></button>
                      <button type="button" class="btn btn-success" id="complete_order"><?= __t('Complete Order') ?></button>
                    </div>
                  </div>
                </div>
              </div>

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

  // Initialize Bootstrap Select for customer dropdown
  $('#customer_dropdown').selectpicker({
    liveSearch: true,
    size: 'auto',
    noneSelectedText: 'Select Customer',
    noneResultsText: 'No customer found'
  });

  var Toast = Swal.mixin({
        toast: true,
        position: 'top-center',
        showConfirmButton: false,
        timer: 3000
  });

  // Initialize cart
  var cart = [];
  var total = 0;


  //Change category
  $(document).on('change', '#category', function(){
    var id = $(this).data('id')
    if(id === ""){
      $.ajax({
        url: '../jquery/sales/pos.php',
        type: 'POST',
        data: {cat_chang:id},
        success: function(response) {
         $('.pswp-gallery').html(response)
  
        }
     
      });
    }else{
      all_pro()
    }


  });

  //Product searching 
  $(document).on('keyup', '#search_product', function(){
    var id = $(this).val()
    if(id != ""){
      $.ajax({
        url: '../jquery/sales/pos.php',
        type: 'POST',
        data: {searchpro:id},
        success: function(response) {
         $('.pswp-gallery').html(response)
  
        }
     
      });
    }else{
      all_pro();
    }
  });

  // Barcode scanning
  var barcodeTimer;
  $(document).on('keyup', '#barcode_scanner', function(){
    var barcode = $(this).val();
    clearTimeout(barcodeTimer);
    
    barcodeTimer = setTimeout(function(){
      if(barcode.length >= 3){ // Minimum barcode length
        $.ajax({
          url: '../jquery/sales/pos.php',
          type: 'POST',
          data: {barcode_scan: barcode},
          success: function(response) {
            if(response !== 'not_found'){
              // Parse the response and add to cart
              var item = JSON.parse(response);
              addItemToCart(item);
              $('#barcode_scanner').val(''); // Clear barcode input
            } else {
              toastr.error("Product not found with barcode: " + barcode);
              $('#barcode_scanner').val('');
            }
          }
        });
      }
    }, 500); // Wait 500ms after user stops typing
  });


  function all_pro(){
    $.ajax({
        url: '../jquery/sales/pos.php',
        type: 'POST',
        data: {allpor:true},
        success: function(response) {
         $('.pswp-gallery').html(response)
  
        }
     
      });
  }

  // Add product to cart
  $(document).on('click', '.img-link', function(e){
    e.preventDefault();
    var itemId = $(this).data('item-id');
    var name = $(this).data('item-name');
    var price = parseFloat($(this).data('item-price'));
    var availableQty = parseInt($(this).data('item-qty'));
    
    if(availableQty <= 0){
      toastr.error("Item out of stock!");
      return;
    }

    // Check if item already in cart
    var existingItem = cart.find(item => item.id === itemId);
    if(existingItem){
      if(existingItem.quantity >= availableQty){
        toastr.error("Cannot add more items. Stock limit reached!");
        return;
      }
      existingItem.quantity += 1;
    } else {
      cart.push({
        id: itemId,
        name: name,
        price: price,
        quantity: 1,
        availableQty: availableQty
      });
    }
    
    updateCart();
    playSound();
  });

  // Update cart display
  function updateCart(){
    var cartHtml = '';
    total = 0;
    
    cart.forEach(function(item, index){
      var itemTotal = item.price * item.quantity;
      total += itemTotal;
      
      cartHtml += `
        <tr>
          <td>${item.name}</td>
          <td style="width:150px;">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend"><span class="input-group-text">$</span></div>
              <input type="number" step="0.01" min="0" class="form-control cart-price-input" value="${item.price.toFixed(2)}" onchange="setPrice(${index}, this.value)">
            </div>
          </td>
          <td style="width:160px;">
            <div class="input-group input-group-sm">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(${index}, -1)">-</button>
              <input type="number" class="form-control cart-qty-input" value="${item.quantity}" min="1" max="${item.availableQty}" onchange="setQuantity(${index}, this.value)">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(${index}, 1)">+</button>
            </div>
          </td>
          <td>$${itemTotal.toFixed(2)}</td>
          <td class="cart-actions"><button class="btn btn-danger btn-sm" onclick="removeItem(${index})"><i class="fa fa-trash"></i></button></td>
        </tr>
      `;
    });
    
    if(cart.length === 0){
      cartHtml = '<tr><td colspan="5" class="text-center">No items in cart</td></tr>';
    }
    
    $('#orderItems').html(cartHtml);
    $('#Total').text(total.toFixed(2) + '$');
  }

  // Update quantity
  window.updateQuantity = function(index, change){
    var item = cart[index];
    var newQty = item.quantity + change;
    
    if(newQty < 1){
      removeItem(index);
      return;
    }
    
    if(newQty > item.availableQty){
      toastr.error("Cannot add more items. Stock limit reached!");
      return;
    }
    
    item.quantity = newQty;
    updateCart();
  };

  // Set quantity directly
  window.setQuantity = function(index, value){
    var item = cart[index];
    var newQty = parseInt(value);
    
    if(newQty < 1){
      removeItem(index);
      return;
    }
    
    if(newQty > item.availableQty){
      toastr.error("Cannot add more items. Stock limit reached!");
      item.quantity = item.availableQty;
      updateCart();
      return;
    }
    
    item.quantity = newQty;
    updateCart();
  };

  // Set unit price directly
  window.setPrice = function(index, value){
    var item = cart[index];
    var newPrice = parseFloat(value);
    if(isNaN(newPrice) || newPrice < 0){
      newPrice = 0;
    }
    item.price = newPrice;
    updateCart();
  };

  // Remove item from cart
  window.removeItem = function(index){
    cart.splice(index, 1);
    updateCart();
  };

  // Reset order
  $('#resetOrder').click(function(){
    cart = [];
    total = 0;
    updateCart();
    toastr.success("Cart cleared!");
  });

  // Play sound when item added
  function playSound(){
    var audio = document.getElementById('sound');
    if(!audio) return;
    try{
      audio.currentTime = 0;
      var p = audio.play();
      if(p && typeof p.then === 'function'){
        p.catch(function(){ /* ignore auto-play rejections */ });
      }
    }catch(e){ /* ignore */ }
  }

  // Customer dropdown selection
  $('#customer_dropdown').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue){
    // Customer selection no longer populates phone/name/email fields
  });

  // Removed manual phone lookup, customer details fields are not used anymore

  function updateTotalsUI(){
    var discount = parseFloat($('#discount_all').val()) || 0;
    if(discount < 0){ discount = 0; }
    var grandTotal = Math.max(total - discount, 0);
    $('#modal_subtotal').text(total.toFixed(2));
    $('#subtotal_readonly').val(total.toFixed(2));
    $('#modal_grand_total').text(grandTotal.toFixed(2));
    $('#grandtotal_readonly').val(grandTotal.toFixed(2));

    var paid = parseFloat($('#amount_paid').val()) || 0;
    var change = paid - grandTotal;
    if(change > 0){
      $('#change_alert').removeClass('d-none').show();
      $('#change_amount').text(change.toFixed(2));
      $('#balance_alert').addClass('d-none').hide();
    } else {
      $('#change_alert').addClass('d-none').hide();
      var balance = grandTotal - paid;
      if(balance > 0){
        $('#balance_alert').removeClass('d-none').show();
        $('#balance_amount').text(balance.toFixed(2));
      } else {
        $('#balance_alert').addClass('d-none').hide();
      }
    }
  }

  $('#amount_paid').on('input', updateTotalsUI);
  $('#discount_all').on('input', updateTotalsUI);

  // Order modal show
  $('#payment').click(function(){
    if(cart.length === 0){
      toastr.error("Please add items to cart first!");
      return;
    }
    $('#modal_subtotal').text(total.toFixed(2));
    $('#discount_all').val(0);
    $('#amount_paid').val(total.toFixed(2));
    updateTotalsUI();
  });

  // Complete order
  $('#complete_order').click(function(){
    var paymentMethod = $('#payment_method').val();
    var amountPaid = parseFloat($('#amount_paid').val()) || 0;
    var discountAll = parseFloat($('#discount_all').val()) || 0;
    var customerId = $('#customer_dropdown').val();
    var notes = $('#order_notes').val();
    
    if(!paymentMethod){
      toastr.error("Please select payment method!");
      return;
    }
    // Allow partial payments; balance will be recorded
    
    // Prepare order data
    var orderData = {
      items: cart,
      total: total,
      discount: discountAll,
      payment_method: paymentMethod,
      amount_paid: amountPaid,
      customer_id: customerId,
      notes: notes
    };
    
    $.ajax({
      url: '../jquery/sales/pos.php',
      type: 'POST',
      dataType: 'json',
      data: {complete_order: JSON.stringify(orderData)},
      success: function(res){
        if(res && res.success === true){
          toastr.success("Order completed successfully!");
          $('#OrderModal').modal('hide');
          cart = [];
          total = 0;
          updateCart();
          $('#orderForm')[0].reset();
          $('#change_alert').hide();
        } else if(res && res.error){
          toastr.error("Error completing order: " + (res.message || res.error));
        } else {
          toastr.error("Unexpected response while completing order.");
          console.log('Complete order response:', res);
        }
      },
      error: function(xhr){
        console.log('AJAX error:', xhr.responseText);
        toastr.error("Error processing order!");
      }
    });
  });

  // Add item to cart function (for barcode scanning)
  function addItemToCart(item){
    if(item.qty <= 0){
      toastr.error("Item out of stock!");
      return;
    }

    // Check if item already in cart
    var existingItem = cart.find(cartItem => cartItem.id === item.id);
    if(existingItem){
      if(existingItem.quantity >= item.qty){
        toastr.error("Cannot add more items. Stock limit reached!");
        return;
      }
      existingItem.quantity += 1;
    } else {
      cart.push({
        id: item.id,
        name: item.name,
        price: parseFloat(item.price),
        quantity: 1,
        availableQty: item.qty
      });
    }
    
    updateCart();
    playSound();
    toastr.success("Item added to cart: " + item.name);
  }

  // Initialize cart display
  updateCart();

})

</script>

<!-- Bootstrap Select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

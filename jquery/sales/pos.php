<?php 
include('../../path.php');
include('../../inc/session_config.php');
session_start();

include('../../inc/config.php');
include('../../inc/access_control.php');

date_default_timezone_set('Africa/Nairobi');

// Complete order
if(isset($_POST['complete_order'])){
    // Check if user has add permission for sales/POS
    secureAjaxEndpoint($con, 'add', null);
    $orderData = json_decode($_POST['complete_order'], true);
    
    $date = date('Y-m-d H:i:s');
    $userid = $_SESSION['uid'];
    // Customer phone/name/email removed from POS complete flow
    $customer_id = mysqli_real_escape_string($con, $orderData['customer_id']);
    $payment_method = mysqli_real_escape_string($con, $orderData['payment_method']);
    $amount_paid = $orderData['amount_paid'];
    $total = $orderData['total'];
    $notes = mysqli_real_escape_string($con, $orderData['notes']);
    
    // Handle customer selection
    if(!empty($customer_id)){
        // Customer selected from dropdown - use existing customer
        $stmt = "SELECT customer_id FROM customer WHERE customer_id = '$customer_id'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            // Customer exists, use it
            $customer_id = $customer_id;
        } else {
            // Customer ID doesn't exist, fallback to default
            $customer_id = 29;
        }
    } else {
        $customer_id = 29; // Default customer
    }
    
    // Generate invoice number
    $stmt = "SELECT COUNT(*) FROM orders WHERE order_status != 'on-going'";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    $index = $row[0] + 1;
    $invoice_number = 'INV-' . sprintf("%04d", $index);
    
    // Apply global discount and calculate balances
    $discount = isset($orderData['discount']) ? floatval($orderData['discount']) : 0;
    if($discount < 0){ $discount = 0; }
    $pr_be_dis = $total;
    $pr_af_dis = max($total - $discount, 0);
    $balance = $pr_af_dis - $amount_paid;
    $payment_status = 'Paid';
    if($amount_paid <= 0 && $pr_af_dis > 0){
        $payment_status = 'Not paid';
    } else if($amount_paid > 0 && $balance > 0){
        $payment_status = 'Partial payment';
    }
    
    // Create order
    $stmt = "INSERT INTO orders (cust_id, order_status, order_by, trans_date, warehouse, ser, pr_be_dis, pr_af_dis, amount, balance, payment_status, discount_on_all) 
             VALUES ($customer_id, 'completed', $userid, '$date', 1, '$invoice_number', $pr_be_dis, $pr_af_dis, $amount_paid, $balance, '$payment_status', $discount)";
    
    if(mysqli_query($con, $stmt)){
        $order_id = mysqli_insert_id($con);
        
        // Add order items
        foreach($orderData['items'] as $item){
            $item_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $subtotal = $quantity * $price;
            
            // Insert order item
            $stmt = "INSERT INTO order_item (order_id, item_id, qty, price, sub_total, pprice) 
                     VALUES ($order_id, $item_id, $quantity, $price, $subtotal, $price)";
            mysqli_query($con, $stmt);
            
            // Update item stock
            $stmt = "UPDATE item SET qty = qty - $quantity WHERE item_id = $item_id";
            mysqli_query($con, $stmt);
        }
        
        echo 'success';
    } else {
        echo 'Error creating order: ' . mysqli_error($con);
    }
    
    exit;
}

if(isset($_POST['cat_chang'])){
    $id = mysqli_real_escape_string($con, $_POST['cat_chang']);

    if(!empty($id)){
        $stmt = "SELECT * FROM item WHERE item_category = '$id' ORDER BY item_name ASC";
        $result = mysqli_query($con,$stmt); 
        if(mysqli_num_rows($result) > 0){
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
                            <img class='img-fluid' src='../../assets/images/products/$img' alt='Card image cap'> <a href='#' class='img-link' data-item-id='$id' data-item-name='$name' data-item-price='$price' data-item-qty='$qty'>
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
                            <p class='text-muted mb-0'> $name ($qty) </p>
                          </figcaption><!-- /.figure-caption -->
                        </figure><!-- /.card-figure -->
                      </div><!-- /.card -->
                    </div>
                
                
                ";
            }
        }else{
            echo "
               <div class='col-md-12'> <div class='alert alert-primary'>No products found</div> </div>
            ";
        }

    }else{
        $stmt = "SELECT * FROM item  ORDER BY item_name ASC";
        $result = mysqli_query($con,$stmt); 
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
                        <img class='img-fluid' src='../../assets/images/products/$img' alt='Card image cap'> <a href='#' class='img-link' data-item-id='$id' data-item-name='$name' data-item-price='$price' data-item-qty='$qty'>
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
    }
}


// All producsts 
if(isset($_POST['allpor'])){
    $stmt = "SELECT * FROM item  ORDER BY item_name ASC";
    $result = mysqli_query($con,$stmt); 
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
                    <p class='text-muted mb-0'> $name ($qty) </p>
                  </figcaption><!-- /.figure-caption -->
                </figure><!-- /.card-figure -->
              </div><!-- /.card -->
            </div>
        
        
        ";
    }
}


//Searching products
if(isset($_POST['searchpro'])){
    $val = mysqli_real_escape_string($con, $_POST['searchpro']);

    $stmt = "SELECT * FROM item  WHERE item_name LIKE '%$val%'";
    $result = mysqli_query($con,$stmt); 
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
}

// Barcode scanning
if(isset($_POST['barcode_scan'])){
    // Check if user has view permission for sales/POS
    secureAjaxEndpoint($con, 'view', null);
    $barcode = mysqli_real_escape_string($con, $_POST['barcode_scan']);
    
    $stmt = "SELECT * FROM item WHERE barcode = '$barcode'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $item = array(
            'id' => $row['item_id'],
            'name' => $row['item_name'],
            'price' => $row['sale_price'],
            'qty' => $row['qty']
        );
        echo json_encode($item);
    } else {
        echo 'not_found';
    }
}

// Customer lookup by phone
if(isset($_POST['get_customer_by_phone'])){
    // Check if user has view permission for sales/POS
    secureAjaxEndpoint($con, 'view', null);
    $phone = mysqli_real_escape_string($con, $_POST['get_customer_by_phone']);
    
    $stmt = "SELECT customer_id, cust_name, cust_phone, cust_email, cust_addr FROM customer WHERE cust_phone = '$phone'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo 'not_found';
    }
}

// Customer lookup by ID
if(isset($_POST['get_customer_by_id'])){
    // Check if user has view permission for sales/POS
    secureAjaxEndpoint($con, 'view', null);
    $customer_id = mysqli_real_escape_string($con, $_POST['get_customer_by_id']);
    
    $stmt = "SELECT customer_id, cust_name, cust_phone, cust_email, cust_addr FROM customer WHERE customer_id = '$customer_id'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo 'not_found';
    }
}

?>
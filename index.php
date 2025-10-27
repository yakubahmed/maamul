<?php include('path.php'); ?>
<?php include( ROOT_PATH . '/inc/config.php' ); ?>
<?php $menu = 'Dashboard'  ?>
      
      <?php $smenu = ''  ?>
      
<?php $title = "Dashboard"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body>
    <!-- .app -->
    <div class="app">
     
      <!--<div class="page-message" role="alert">Macamiil fadlan <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>-->
     <?php
     
        function shortNumber($num) 
        {
            $units = ['', 'K', 'M', 'B', 'T'];
            for ($i = 0; $num >= 1000; $i++) {
                $num /= 1000;
            }
            return round($num, 1) . $units[$i];
        }
     
     ?>
     <?php $menu = 'Dashboard'; ?>
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
              <h2 class="section-title"> Dashboard </h2><!-- metric row -->
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                <div class="section-block">
                  
                  <!-- Current Balance - Full Width -->
                  <div class="metric-row mt-2">
                    <div class="col-12">
                      <div class="metric metric-bordered">
                        <h2 class="metric-label"> Current Balance </h2>
                        <?php 
                          $salesPaid = 0; 
                          $purchasePaid = 0; 
                          $expenseTotal = 0; 

                          // Total sales paid amount (customer payments)
                          $res = mysqli_query($con, "SELECT SUM(amount) FROM payment");
                          if($res){
                            $r = mysqli_fetch_array($res);
                            if(!empty($r[0])){ $salesPaid = (float)$r[0]; }
                          }

                          // Total purchase paid amount (supplier payments)
                          $res = mysqli_query($con, "SELECT SUM(amount) FROM pur_payments");
                          if($res){
                            $r = mysqli_fetch_array($res);
                            if(!empty($r[0])){ $purchasePaid = (float)$r[0]; }
                          }

                          // Total expenses
                          $res = mysqli_query($con, "SELECT SUM(amount) FROM expense");
                          if($res){
                            $r = mysqli_fetch_array($res);
                            if(!empty($r[0])){ $expenseTotal = (float)$r[0]; }
                          }

                          // Balance = Sales Paid - Purchase Paid - Expenses
                          $currentBalance = $salesPaid - $purchasePaid - $expenseTotal;
                          $balanceFull = number_format($currentBalance, 2, '.', ',');
                          $isNegative = $currentBalance < 0;
                          $valueClass = $isNegative ? 'text-danger' : 'text-success';
                          $sign = $isNegative ? '-' : '';
                          $shortVal = empty($currentBalance) ? '0.00' : shortNumber(abs($currentBalance));
                        ?>
                        <p class="metric-value h1" data-placement="top" title="<?= $balanceFull ?>">
                          <sup>$</sup> <span class="value <?= $valueClass ?>"><?php echo $sign . $shortVal; ?></span>
                        </p>
                        <p class="text-muted small mb-0">Sales paid − Purchases paid − Expenses</p>
                      </div>
                    </div>
                  </div>

                  <div class="metric-row">
                    <!-- metric column -->
                    <div class="col-md-3">
                      <!-- .metric -->
                      <div class="metric metric-bordered">
                        <h2 class="metric-label"> Total Sales </h2>
                        <?php 
                          $stmt  = "SELECT sum(pr_af_dis) FROM orders";
                          $result = mysqli_query($con, $stmt);
                          $row = mysqli_fetch_array($result); ?>
                            <p class="metric-value h1" data-placement="top" title="<?php echo $row[0]; ?>">
                                <sup>$</sup> <span class="value">
                                 <?php if ( empty( shortNumber($row[0])  )) { echo "0.00"; }else { echo shortNumber($row[0]);}  ?>
                                </span>
                            </p>
                      </div><!-- /.metric -->
                    </div><!-- /metric column -->
                    <!-- metric column -->
                    <div class="col-md-3">
                      <!-- .metric -->
                      <div class="metric metric-bordered">
                        <h2 class="metric-label"> Total Expense </h2>
                          <?php 
                              $stmt  = "SELECT SUM(amount) FROM expense";
                              $result = mysqli_query($con, $stmt);
                              $row = mysqli_fetch_array($result); ?>
                                
                        <p class="metric-value h1" data-placement="top" title="<?= $row[0] ?>">
                          <sup>$</sup> <span class="value">
                            <?php if ( empty(shortNumber($row[0]))) { echo "0.00"; }else { echo shortNumber($row[0]);};
                              
                              ?>
                          </span>
                        </p>
                      </div><!-- /.metric -->
                    </div><!-- /metric column -->
                    <!-- metric column -->
                    <div class="col-md-3">
                      <!-- .metric -->
                      <div class="metric metric-bordered">
                        <h2 class="metric-label"> Total Sales Due </h2>
                          <?php 
                              $stmt  = "SELECT SUM(balance) FROM orders";
                              $result = mysqli_query($con, $stmt);
                              $row = mysqli_fetch_array($result);?>
                        <p class="metric-value h1" data-placement="top" title="<?= $row[0] ?>">
                          <sup>$</sup> <span class="value">
                            <?php 
                            
                            if ( empty(shortNumber($row[0]))) { echo "0.00"; }else { echo shortNumber($row[0]);};
                              
                            ?>
                          </span>
                        </p>
                      </div><!-- /.metric -->
                    </div><!-- /metric column -->
                    <!-- metric column -->
                    <div class="col-md-3">
                      <!-- .metric -->
                      <div class="metric metric-bordered">
                        <h2 class="metric-label"> Total Purchases </h2>
                      <?php 
                          $stmt  = "SELECT SUM(gtotal) FROM purchase";
                          $result = mysqli_query($con, $stmt);
                          $row = mysqli_fetch_array($result);    ?>
                        <p class="metric-value h1" data-placement="top" title="<?= $row[0] ?>">
                          <sup>$</sup> <span class="value">
                              <?php  if ( empty($row[0])) { echo "0.00"; }else { echo shortNumber($row[0]);} ?>
                          </span>
                        </p>
                      </div><!-- /.metric -->
                    </div><!-- /metric column -->

                    
                  </div><!-- /metric row -->

                  <!-- Current Balance Widget moved to top row -->
                  <div class="">
                      <div class="metric-row ">
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-warning text-light"><i class="fa fa-list"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Items</span>
                                <span class="info-box-number"><strong>
                                  <?php 
                                    $stmt  = "SELECT COUNT(*) FROM item";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_array($result)){
                                      echo $row[0];
                                    }
                                  ?>
                                </strong></span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-success text-light"><i class="fa fa-users"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Customers</span>
                                <span class="info-box-number"> <strong>
                                  <?php 
                                    $stmt  = "SELECT COUNT(*) FROM customer";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_array($result)){
                                      echo $row[0];
                                    }
                                  ?>
                                </strong> </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-primary text-light"><i class="fa fa-truck"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Suppliers</span>
                                <span class="info-box-number"> <strong>
                                <?php 
                                    $stmt  = "SELECT COUNT(*) FROM supplier";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_array($result)){
                                      echo $row[0];
                                    }
                                  ?>
                                </strong> </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-danger text-white"><i class="fa fa-user-graduate"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Employee's</span>
                                <span class="info-box-number">
                                <?php 
                                    $stmt  = "SELECT COUNT(*) FROM users";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_array($result)){
                                      echo $row[0];
                                    }
                                  ?>
                                </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                          
                          
                        </div>

                      <!-- Additional Important Metrics Row -->
                      <div class="metric-row mt-3">
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-info text-light"><i class="fa fa-hand-holding-usd"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Purchase Due</span>
                                <span class="info-box-number"><strong>
                                  <?php 
                                    $stmt  = "SELECT SUM(balance) FROM purchase";
                                    $result = mysqli_query($con, $stmt);
                                    if($result){
                                      $row = mysqli_fetch_array($result);
                                      if(empty($row[0])) { echo "$0.00"; } else { echo "$" . shortNumber($row[0]); }
                                    } else {
                                      echo "$0.00";
                                    }
                                  ?>
                                </strong></span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-warning text-light"><i class="fa fa-exclamation-triangle"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Low Stock Items</span>
                                <span class="info-box-number"> <strong>
                                  <?php 
                                    // Check if min_qty column exists, otherwise use default value of 10
                                    $stmt  = "SELECT COUNT(*) FROM item WHERE qty <= 10";
                                    $result = mysqli_query($con, $stmt);
                                    if($result){
                                      $row = mysqli_fetch_array($result);
                                      echo $row[0];
                                    } else {
                                      echo "0";
                                    }
                                  ?>
                                </strong> </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-success text-light"><i class="fa fa-shopping-cart"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Today's Sales</span>
                                <span class="info-box-number"> <strong>
                                <?php 
                                    $today = date('Y-m-d');
                                    $stmt  = "SELECT SUM(pr_af_dis) FROM orders WHERE DATE(order_date) = '$today'";
                                    $result = mysqli_query($con, $stmt);
                                    if($result){
                                      $row = mysqli_fetch_array($result);
                                      if(empty($row[0])) { echo "$0.00"; } else { echo "$" . shortNumber($row[0]); }
                                    } else {
                                      echo "$0.00";
                                    }
                                  ?>
                                </strong> </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                        <!-- metric column -->
                          <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                              <span class="info-box-icon bg-primary text-white"><i class="fa fa-truck-loading"></i></span>

                              <div class="info-box-content">
                                <span class="info-box-text">Today's Purchases</span>
                                <span class="info-box-number">
                                <?php 
                                    $today = date('Y-m-d');
                                    $stmt  = "SELECT SUM(gtotal) FROM purchase WHERE DATE(pur_date) = '$today'";
                                    $result = mysqli_query($con, $stmt);
                                    if($result){
                                      $row = mysqli_fetch_array($result);
                                      if(empty($row[0])) { echo "$0.00"; } else { echo "$" . shortNumber($row[0]); }
                                    } else {
                                      echo "$0.00";
                                    }
                                  ?>
                                </span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                          </div>     
                          
                          
                        </div>

                    </div>
                </div>
                </div><!-- /.section-block -->
                <!-- grid row -->
                <div class="row">
                  <!-- grid column -->
                  <div class="card col-md-12">
                    <div class="card-header">
                      Sales & Purchase report
                    </div>
                    <div class="card-body">
                        <!-- legend -->
                        <ul class="list-inline medium">
                          <li class="list-inline-item">
                            <i class="fa fa-fw fa-circle text-teal"></i> Sales </li>
                          <li class="list-inline-item">
                            <i class="fa fa-fw fa-circle text-purple"></i> Purchases </li>
                        </ul><!-- /legend -->
                        <div class="chartjs"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="canvas-achievement" style="display: block; width: 653px; height: 250px;" width="653" height="250" class="chartjs-render-monitor"></canvas>
                  </div>

                    </div>

                </div>

              <div class="col-md-12">
                  <!-- .card -->
                  <div class="card card-fluid">
                      <!-- .card-body -->
                      <div class="card-body">
                        <h3 class="card-title"> Top(10) selling items </h3>
                        
                        <div class="">
                          <div class="list-group list-group-bordered list-group-reflow">

                          <?php
                            $sql = "SELECT SUM(qty) FROM order_item ";
                            $re = mysqli_query($con, $sql);
                            $rw = mysqli_fetch_array($re);
                            $total  = $rw[0];
                            
                            
                            $stmt = "SELECT item.item_name, SUM(order_item.qty) FROM item, order_item WHERE item.item_id = order_item.item_id GROUP by item.item_id ORDER BY SUM(order_item.qty) DESC LIMIT 10"; 
                            $result = mysqli_query($con, $stmt);
                            while($row = mysqli_fetch_array($result)){
                              $name = $row[0];
                              $items = array("text-info", "text-green", "text-yellow", "text-success", "text-black", "text-red", "text-blue", "text-cyan", "text-tail");
                              $qty = $row[1];
                              $per = $qty / $total * 100;
                              $per = number_format($per, 0);
                              
                             echo "
                             <div class='list-group-item justify-content-between align-items-center'>
                             <span><i class='fas fa-square " . $items[array_rand($items)] ." mr-2'></i> $name</span> <span class='text-muted'> <strong>$per%</strong> </span>
                             </div>
                             ";

                            }
                          ?>
                        <!-- .progress -->

                        </div><!-- /.progress -->
                      </div><!-- /.card-body -->
                      <!-- .list-group -->
                  
                      </div><!-- /.list-group -->
                    </div><!-- /.card -->
              </div>

              <div class="col-md-12">
              <div class="card">
                  <!-- .card-header -->
                  <div class="card-header border-0"> Last orders </div><!-- /.card-header -->
                  <!-- .table-responsive -->
                  <div class="table-responsive">
                    <!-- .table -->
                    <table class="table table-hover">
                      <!-- thead -->
                      <thead>
                        <tr>
                          <th style="min-width:100px"> Invoice number </th>
                          <th> Date </th>
                          <th style="min-width:250px"> Customer </th>
                          <th style="min-width: 100px"> Order status </th>
                          <th style="width:100">Payment status</th>
                        </tr>
                      </thead><!-- /thead -->
                      <!-- tbody -->
                      <tbody>
                        <?php

                            $stmt = "SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
                            customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
                            FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' ORDER BY orders.order_id DESC LIMIT 10";
                            $result = mysqli_query($con, $stmt);
                            while($row = mysqli_fetch_assoc($result)){
                              $id = $row['order_id'];
                              $ser = $row['ser'];
                              $date = date('d-m-Y', strtotime($row['order_date'])) ;
                              $cust = $row['cust_name'];
                              $cphone = $row['cust_phone'];
                              $ostatus = $row['order_status'];
                              $pamount = $row['amount'];
                              $balance = $row['balance'];
                              $tamount = $row['pr_af_dis'];
                              $pstatus = $row['payment_status'];

                              $addPay = "";
                              if($pstatus != "Paid"){
                                $addPay =  "<button type='button' data-toggle='modal' data-target='#addPayModal' id='btnAddPay' data-id='$id'  class='dropdown-item'> <i class='fa fa-plus'></i> Add payment</button>";
                              }

                              if($pstatus == "Not paid"){ $pstatus = " <span class='badge  badge-danger'>$pstatus</span> "; }
                              else if($pstatus == "Partial payment" ){  $pstatus = " <span class='badge badge-warning'>$pstatus</span> "; }
                              else if($pstatus == "Paid" ){  $pstatus = " <span class='badge  badge-success'>$pstatus</span> "; }
                              
                              if($ostatus == "Confirmed"){
                                $ostatus = "<span class='badge  badge-success'>$ostatus</span>";
                              }else if($ostatus == "Delivered"){
                                $ostatus = "<span class='badge  badge-info'>$ostatus</span>";
                              }else if($ostatus == "Ordered"){
                                $ostatus = "<span class='badge  badge-warning'>$ostatus</span>";

                              }
                              
                              echo "
                                <tr>
                                  <td>$ser</td>
                                  <td>$date</td>
                                  <td>$cust</td>
                                  <td> $ostatus </td>

                                  <td> $pstatus</td>
                                  <td>

                            

                                </tr>
                              ";
                            }
                        ?>
                  
                      </tbody><!-- /tbody -->
                    </table><!-- /.table -->
                  </div><!-- /.table-responsive -->
                  <!-- .card-footer -->
                  <div class="card-footer">
                    <a href="<?= BASE_URL ?>sales/history" class="card-footer-item">View report <i class="fa fa-fw fa-angle-right"></i></a>
                  </div><!-- /.card-footer -->
                </div>
              </div>
                  
              </div><!-- /.page-section -->
            </div><!-- /.page-inner -->
          </div><!-- /.page -->
        </div><!-- .app-footer -->
          <?php include(ROOT_PATH .'/inc/footer.php'); ?>
        <!-- /.app-footer -->
        <!-- /.wrapper -->
      </main><!-- /.app-main -->
    </div><!-- /.app -->

    <script></script>

<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>
<script src="<?= BASE_URL ?>assets/javascript/pages/team-overview-demo.js"></script> <!-- END PAGE LEVEL JS -->

<?php 

function jan_sl(){
  global $con;

  $jan = date('Y-01');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%'";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function feb_sl(){
  global $con;

  $jan = date('Y-02');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function mar_sl(){
  global $con;
  $jan = date('Y-03');

  $jan_start = date('d',strtotime(date('Y-03-01')));
  $jan_end =  cal_days_in_month(CAL_GREGORIAN, 3, date('Y')); 
  $stmt = "SELECT COUNT(*) FROM orders WHERE  order_date like '$jan%'";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function apr_sl(){
  global $con;

  $jan = date('Y-04');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function may_sl(){
  global $con;

  $jan = date('Y-05');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function june_sl(){
  global $con;

  $jan = date('Y-06');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function jul_sl(){
  global $con;
  $jan = date('Y-07');


  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function aug_sl(){
  global $con;
  $jan = date('Y-08');

 
  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function sep_sl(){
  global $con;

  $jan = date('Y-09');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%'";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function oct_sl(){
  global $con;

  $jan = date('Y-10');

  $jan_start = date('d',strtotime(date('Y-10-01')));
  $jan_end =  cal_days_in_month(CAL_GREGORIAN, 10, date('Y')); 
  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function nov_sl(){
  global $con;

  $jan = date('Y-11');

  $stmt = "SELECT COUNT(*) FROM orders WHERE  order_date like '$jan%'";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function dec_sl(){
  global $con;

  $jan = date('Y-12');

  $stmt = "SELECT COUNT(*) FROM orders WHERE order_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function jan_pr(){
  global $con;
  $jan = date('Y-01');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%' ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function feb_pr(){
  global $con;
  $jan = date('Y-02');
 
  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function mar_pr(){
  global $con;
  $jan = date('Y-03');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function apr_pr(){
  global $con;

  $jan = date('Y-04');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function may_pr(){
  global $con;
  $jan = date('Y-05');
 
  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}
    return $res;
  }

}

function june_pr(){
  global $con;

  $jan = date('Y-06');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function jul_pr(){
  global $con;

  $jan = date('Y-07');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function aug_pr(){
  global $con;

  $jan = date('Y-08');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function sep_pr(){
  global $con;

  $jan = date('Y-09');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function oct_pr(){
  global $con;
  $jan = date('Y-10');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function nov_pr(){
  global $con;

  $jan = date('Y-11');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}

function dec_pr(){
  global $con;

  $jan = date('Y-12');

  $stmt = "SELECT COUNT(*) FROM purchase WHERE pur_date like '$jan%'  ";
  $result = mysqli_query($con, $stmt);
  if($row = mysqli_fetch_array($result)){
    $res = $row[0]; if($res == ""){ $res = 0;}

    return $res;
  }

}


?>
<script>
  // Team Overview Demo
// =============================================================
var TeamOverviewDemo = /*#__PURE__*/function () {
  function TeamOverviewDemo() {
    _classCallCheck(this, TeamOverviewDemo);

    this.init();
  }

  _createClass(TeamOverviewDemo, [{
    key: "init",
    value: function init() {
      // event handlers
      this.achievementChart();
    }
  }, {
    key: "achievementChart",
    value: function achievementChart() {
      var data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep' ,'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Sales',
          borderColor: Looper.colors.brand.teal,
          backgroundColor: Looper.colors.brand.teal,
          fill: false,
          data: [<?= jan_sl() ?>, <?= feb_sl()?>, <?= mar_sl() ?>, <?=apr_sl() ?>, <?= may_sl() ?>, <?= june_sl() ?>, <?= jul_sl()?>, <?= aug_sl() ?>,<?= sep_sl() ?>, <?= oct_sl() ?>, <?= nov_sl() ?>, <?= dec_sl() ?>]
        }, {
          label: 'Purchases',
          borderColor: Looper.colors.brand.purple,
          backgroundColor: Looper.colors.brand.purple,
          fill: false,
          data: [<?= jan_pr() ?>, <?= feb_pr()?>, <?= mar_pr() ?>,  <?=apr_pr() ?>,  <?= may_pr() ?>, <?= june_pr() ?>, <?= jul_pr()?>, <?= aug_pr() ?>, <?= sep_pr() ?>, <?= oct_pr() ?>, <?= nov_pr() ?>, <?= dec_pr() ?>]
        }]
      }; // init achievement chart

      var canvas = $('#canvas-achievement')[0].getContext('2d');
      var chart = new Chart(canvas, {
        type: 'bar',
        data: data,
        options: {
          responsive: true,
          legend: {
            display: false
          },
          title: {
            display: false
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true,
                drawBorder: false,
                drawOnChartArea: false
              }
            }],
            yAxes: [{
              gridLines: {
                display: true,
                borderDash: [8, 4]
              },
              ticks: {
                stepSize: 20
              }
            }]
          }
        }
      });
    }
  }]);

  return TeamOverviewDemo;
}();
</script>




</body>
</html>

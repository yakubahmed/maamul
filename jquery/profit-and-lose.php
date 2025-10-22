<?php 

session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');


if(isset($_POST['fdate'])){
    $fdate  = $_POST['fdate'];
    $sdate  = $_POST['sdate'];


    $stmt = "SELECT SUM(sub_total)   FROM order_item WHERE order_id IN  ( SELECT order_id FROM orders WHERE  order_date >= '$fdate' AND order_date <= '$sdate') ";
    $result = mysqli_query($con, $stmt);
    $row = mysqli_fetch_array($result);
    $saleprice = $row[0];

    if(empty($row[0])){$row[0] = 0;}
    $i = $row[0];
    
    $sql = "SELECT SUM(pprice * qty)   FROM order_item WHERE order_id IN  ( SELECT order_id FROM orders WHERE  order_date >= '$fdate' AND order_date <= '$sdate')";
    $re =mysqli_query($con, $sql);
    $rw = mysqli_fetch_array($re);
    if(empty($rw[0])){$rw[0] = 0;}
    $purchase = $rw[0];

    $gross_profit =  $saleprice - $purchase;

    if($i == "") { $i = 0; }
    echo "
    <div class='col-md-12'>
    <div class='metric-row'>
    <div class='col-md-6'>
    <!-- .metric -->
    <div class='metric metric-bordered'>
        <h2 class='metric-label'> Gross profit </h2>
        <p class='metric-value h1'>
        <sup>$</sup> <span class='value'>
            $gross_profit                        </span>
        </p>
    </div><!-- /.metric -->
    </div><!-- /metric column -->

    ";
        
    $s = "SELECT SUM(amount) FROM expense WHERE reg_date >= '$fdate' AND reg_date <= '$sdate' ";
    $r = mysqli_query($con, $s);
    $ro = mysqli_fetch_array($r);
    $exp = $ro[0];

    $nprofit =  $gross_profit -  $exp;

    echo "
    <div class='col-md-6'>
    <!-- .metric -->
    <div class='metric metric-bordered'>
        <h2 class='metric-label'> Net profit </h2>
        <p class='metric-value h1'>
        <sup>$</sup> <span class='value'>
            $nprofit                        </span>
        </p>
    </div><!-- /.metric -->
    </div><!-- /metric column -->
    </div></div>
    ";




    echo "
    
    <div class='col-lg-12 col-md-12'>
    <!-- .list-group -->
    <div class='list-group mb-3'>
      <div class='list-group-header bg-primary text-light'> <h6>Profit Reports By Orders</h6> </div>
      <li class='list-group-item d-flex justify-content-between align-items-center'>Sales (+) <span class='badge badge-primary' style='font-size: 16px;'> <strong>$i $</strong> </span></li>
      <li class='list-group-item d-flex justify-content-between align-items-center'>Purchase (-) <span class='badge badge-primary' style='font-size: 16px;'> <strong>$purchase $</strong> </span></li>
      <li class='list-group-item d-flex justify-content-between align-items-center'>Expense (-) <span class='badge badge-primary' style='font-size: 16px;'> <strong>$exp $</strong> </span></li>
      <li class='list-group-item d-flex justify-content-between align-items-center active'>Gross profit <span class='badge badge-primary' style='font-size: 16px;'> <strong>$gross_profit $</strong> </span></li>
      <li class='list-group-item d-flex justify-content-between align-items-center active'>Net profit <span class='badge badge-primary' style='font-size: 16px;'> <strong>$nprofit $</strong> </span></li>
    </div><!-- /.list-group -->
  </div>
    ";

    // Add detailed product profit analysis
    echo "
    <div class='col-lg-12 col-md-12 mt-4'>
    <div class='card'>
        <div class='card-header bg-success text-light'>
            <h5 class='mb-0'><i class='fa fa-chart-line'></i> Product Profit Analysis</h5>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-striped table-hover'>
                    <thead class='bg-light'>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity Sold</th>
                            <th>Sale Price</th>
                            <th>Purchase Price</th>
                            <th>Total Sales</th>
                            <th>Total Cost</th>
                            <th>Profit</th>
                            <th>Profit %</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    // Get detailed product profit data
    $product_profit_sql = "SELECT 
        i.item_name,
        SUM(oi.qty) as total_qty,
        oi.price as sale_price,
        oi.pprice as purchase_price,
        SUM(oi.sub_total) as total_sales,
        SUM(oi.pprice * oi.qty) as total_cost,
        SUM(oi.sub_total) - SUM(oi.pprice * oi.qty) as profit,
        ROUND(((SUM(oi.sub_total) - SUM(oi.pprice * oi.qty)) / SUM(oi.sub_total)) * 100, 2) as profit_percentage
    FROM order_item oi 
    JOIN item i ON oi.item_id = i.item_id 
    JOIN orders o ON oi.order_id = o.order_id 
    WHERE o.order_date >= '$fdate' AND o.order_date <= '$sdate'
    GROUP BY i.item_id, i.item_name, oi.price, oi.pprice
    ORDER BY profit DESC";
    
    $product_result = mysqli_query($con, $product_profit_sql);
    if($product_result && mysqli_num_rows($product_result) > 0) {
        while($product_row = mysqli_fetch_assoc($product_result)) {
            $item_name = $product_row['item_name'];
            $total_qty = $product_row['total_qty'];
            $sale_price = number_format($product_row['sale_price'], 2);
            $purchase_price = number_format($product_row['purchase_price'], 2);
            $total_sales = number_format($product_row['total_sales'], 2);
            $total_cost = number_format($product_row['total_cost'], 2);
            $profit = number_format($product_row['profit'], 2);
            $profit_percentage = $product_row['profit_percentage'];
            
            $profit_class = $profit_percentage >= 20 ? 'text-success' : ($profit_percentage >= 10 ? 'text-warning' : 'text-danger');
            
            echo "
            <tr>
                <td><strong>$item_name</strong></td>
                <td>$total_qty</td>
                <td>$$sale_price</td>
                <td>$$purchase_price</td>
                <td>$$total_sales</td>
                <td>$$total_cost</td>
                <td class='$profit_class'><strong>$$profit</strong></td>
                <td class='$profit_class'><strong>$profit_percentage%</strong></td>
            </tr>";
        }
    } else {
        echo "
        <tr>
            <td colspan='8' class='text-center text-muted'>No sales data found for the selected period</td>
        </tr>";
    }
    
    echo "
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>";

}



?>
<?php 
include('../../path.php');

include('../../inc/session_config.php');
session_start();

include('../../inc/config.php');

// Set proper headers for AJAX requests
header('Content-Type: application/json');

date_default_timezone_set('Africa/Nairobi');

// Check if user is logged in
if(!isset($_SESSION['uid']) || !isset($_SESSION['isLogedIn'])){
    echo json_encode(array('error' => 'not_logged_in'));
    exit;
}

// Get filter parameters
$category = isset($_POST['category']) ? mysqli_real_escape_string($con, $_POST['category']) : '';
$stock_status = isset($_POST['stock_status']) ? mysqli_real_escape_string($con, $_POST['stock_status']) : '';
$unit = isset($_POST['unit']) ? mysqli_real_escape_string($con, $_POST['unit']) : '';
$search = isset($_POST['search']) ? mysqli_real_escape_string($con, $_POST['search']) : '';

// Build the query
$sql = "SELECT i.item_id, i.item_name, i.pur_price, i.sale_price, i.qty, 
               ic.category_name, u.unit_name, u.shortname
        FROM item i 
        LEFT JOIN item_category ic ON i.item_category = ic.itemcat_id 
        LEFT JOIN unit u ON i.unit = u.unit_id 
        WHERE 1=1";

$params = array();

// Add filters
if(!empty($category)){
    $sql .= " AND i.item_category = ?";
    $params[] = $category;
}

if(!empty($unit)){
    $sql .= " AND i.unit = ?";
    $params[] = $unit;
}

if(!empty($search)){
    $sql .= " AND i.item_name LIKE ?";
    $params[] = "%$search%";
}

// Add stock status filter
if(!empty($stock_status)){
    switch($stock_status){
        case 'in_stock':
            $sql .= " AND i.qty > 10";
            break;
        case 'low_stock':
            $sql .= " AND i.qty > 0 AND i.qty <= 10";
            break;
        case 'out_of_stock':
            $sql .= " AND i.qty = 0";
            break;
    }
}

$sql .= " ORDER BY i.item_name ASC";

// Execute query
$stmt = mysqli_prepare($con, $sql);
if($stmt){
    if(!empty($params)){
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($result){
        $items = array();
        $total_items = 0;
        $total_value = 0;
        
        while($row = mysqli_fetch_assoc($result)){
            $items[] = $row;
            $total_items++;
            $total_value += ($row['qty'] * $row['pur_price']);
        }
        
        // Generate HTML table
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead class="thead-dark">';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Item Name</th>';
        $html .= '<th>Category</th>';
        $html .= '<th>Purchase Price</th>';
        $html .= '<th>Sale Price</th>';
        $html .= '<th>Current Stock</th>';
        $html .= '<th>Unit</th>';
        $html .= '<th>Stock Value</th>';
        $html .= '<th>Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $counter = 1;
        foreach($items as $item){
            $stock_value = $item['qty'] * $item['pur_price'];
            $status = '';
            $status_class = '';
            
            if($item['qty'] == 0){
                $status = 'Out of Stock';
                $status_class = 'badge-danger';
            } elseif($item['qty'] <= 10){
                $status = 'Low Stock';
                $status_class = 'badge-warning';
            } else {
                $status = 'In Stock';
                $status_class = 'badge-success';
            }
            
            $html .= '<tr>';
            $html .= '<td>' . $counter . '</td>';
            $html .= '<td><strong>' . htmlspecialchars($item['item_name']) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($item['category_name'] ?: 'N/A') . '</td>';
            $html .= '<td>$' . number_format($item['pur_price'], 2) . '</td>';
            $html .= '<td>$' . number_format($item['sale_price'], 2) . '</td>';
            $html .= '<td><strong>' . number_format($item['qty']) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($item['unit_name'] ?: 'N/A') . ' (' . htmlspecialchars($item['shortname'] ?: 'N/A') . ')</td>';
            $html .= '<td>$' . number_format($stock_value, 2) . '</td>';
            $html .= '<td><span class="badge ' . $status_class . '">' . $status . '</span></td>';
            $html .= '</tr>';
            
            $counter++;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        // Add summary
        $html .= '<div class="row mt-4">';
        $html .= '<div class="col-md-6">';
        $html .= '<div class="card bg-light">';
        $html .= '<div class="card-body">';
        $html .= '<h5>Summary</h5>';
        $html .= '<p><strong>Total Items:</strong> ' . $total_items . '</p>';
        $html .= '<p><strong>Total Stock Value:</strong> $' . number_format($total_value, 2) . '</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        echo json_encode(array(
            'success' => true,
            'html' => $html,
            'total_items' => $total_items,
            'total_value' => $total_value
        ));
        
    } else {
        echo json_encode(array('error' => 'query_failed', 'message' => mysqli_error($con)));
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array('error' => 'prepare_failed', 'message' => mysqli_error($con)));
}

mysqli_close($con);
?>

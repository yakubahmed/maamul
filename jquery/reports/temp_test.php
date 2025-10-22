<?php 
include('../../inc/session_config.php');
session_start();

include('../../inc/config.php');
include('../../inc/access_control.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['action']) && $_POST['action'] == 'get_items'){
    // Check if user has view permission for reports
    // secureAjaxEndpoint($con, 'view', null);
    
    $category = isset($_POST['category']) ? mysqli_real_escape_string($con, $_POST['category']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($con, $_POST['status']) : '';
    $search = isset($_POST['search']) ? mysqli_real_escape_string($con, $_POST['search']) : '';
    
    // Build the query
    $where_conditions = array();
    
    if(!empty($category)) {
        $where_conditions[] = "item.item_category = '$category'";
    }
    
    if(!empty($status)) {
        $where_conditions[] = "item.status = '$status'";
    }
    
    if(!empty($search)) {
        $where_conditions[] = "(item.item_name LIKE '%$search%' OR item.barcode LIKE '%$search%')";
    }
    
    $where_clause = '';
    if(!empty($where_conditions)) {
        $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
    }
    
    $stmt = "SELECT 
                item.*,
                item_category.category_name,
                unit.unit_name,
                CONCAT('" . BASE_URL . "assets/images/products/', item.item_image) as image_url
            FROM item 
            LEFT JOIN item_category ON item.item_category = item_category.itemcat_id 
            LEFT JOIN unit ON item.unit = unit.unit_id 
            $where_clause
            ORDER BY item.item_name ASC";
    
    $result = mysqli_query($con, $stmt);
    
    if(!$result) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($con)
        ));
        exit();
    }
    
    $items = array();
    $total_items = 0;
    $active_items = 0;
    $inactive_items = 0;
    $total_stock_value = 0;
    $total_profit_margin = 0;
    $items_with_profit = 0;
    
    while($row = mysqli_fetch_assoc($result)) {
        $item = array(
            'item_id' => $row['item_id'],
            'item_name' => $row['item_name'],
            'category_name' => $row['category_name'],
            'barcode' => $row['barcode'],
            'current_qty' => $row['qty'],
            'unit_name' => $row['unit_name'],
            'sale_price' => $row['sale_price'],
            'purchase_price' => $row['pur_price'],
            'status' => $row['status'],
            'image_url' => $row['image_url']
        );
        
        $items[] = $item;
        $total_items++;
        
        if($row['status'] == 'Active') {
            $active_items++;
        } else {
            $inactive_items++;
        }
        
        // Calculate stock value
        $stock_value = $row['qty'] * $row['pur_price'];
        $total_stock_value += $stock_value;
        
        // Calculate profit margin
        if($row['sale_price'] > 0) {
            $profit_margin = (($row['sale_price'] - $row['pur_price']) / $row['sale_price']) * 100;
            $total_profit_margin += $profit_margin;
            $items_with_profit++;
        }
    }
    
    // Calculate average profit margin
    $avg_profit_margin = $items_with_profit > 0 ? round($total_profit_margin / $items_with_profit, 1) : 0;
    
    $summary = array(
        'total_items' => $total_items,
        'active_items' => $active_items,
        'inactive_items' => $inactive_items,
        'total_stock_value' => $total_stock_value,
        'avg_profit_margin' => $avg_profit_margin
    );
    
    echo json_encode(array(
        'success' => true,
        'data' => $items,
        'summary' => $summary
    ));
    
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Invalid action'
    ));
}
?>

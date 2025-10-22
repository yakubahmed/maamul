<?php 
include('../path.php');

include('../inc/session_config.php');
session_start();

include('../inc/config.php');
include('../inc/access_control.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['iname'])){
    // Check if user has add permission for items
    secureAjaxEndpoint($con, 'add', 8); // Submenu ID 8 for items/add
    
    // Validate required fields
    if(empty($_POST['iname']) || empty($_POST['category']) || empty($_POST['unit'])){
        echo "missing_fields";
        exit;
    }
    
    $iname = mysqli_real_escape_string($con, $_POST['iname']);
    $category = intval($_POST['category']);
    $unit = intval($_POST['unit']);
    $qty = floatval($_POST['qty']);
    $pprice = floatval($_POST['pprice']);
    $sprice = floatval($_POST['sprice']);
    $rdate = mysqli_real_escape_string($con, $_POST['rdate']);
    $status = 'active';
    
    // Validate numeric values
    if($qty < 0 || $pprice < 0 || $sprice < 0){
        echo "invalid_values";
        exit;
    }
    
    // Handle barcode input - use provided barcode or generate random one
    $barcode_input = mysqli_real_escape_string($con, $_POST['barcode']);
    if(empty($barcode_input)){
        $barcode = rand();
    } else {
        $barcode = $barcode_input;
    }

    // Handle file upload
    $newfilename = "";
    if(!empty($_FILES["iimage"]["name"])){
        $temp = explode(".", $_FILES["iimage"]["name"]);
        $newfilename = rand() . date('m-d-y-h-i-s') . '.' . end($temp);
        
        // Check if upload was successful
        if(!move_uploaded_file($_FILES["iimage"]["tmp_name"], "../assets/images/products/" . $newfilename)){
            error_log("Failed to upload image: " . $_FILES["iimage"]["error"]);
            // Continue without image
            $newfilename = "";
        }
    }

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    // Check for duplicate item name
    $stmt = "SELECT * FROM item WHERE item_name = '$iname'";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($result) > 0){
        echo "found_product";
        exit;
    }
    
    // Check for duplicate barcode
    $stmt = "SELECT * FROM item WHERE barcode = '$barcode'";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($result) > 0){
        echo "found_barcode";
        exit;
    }
    
    // Insert item
    $stmt = "INSERT INTO item (item_name, item_category, item_type, item_cost, item_price, unit, qty, pur_price, 
    sale_price, item_image, status, barcode, recived_date, updated_date, warehouse, reg_by)
    VALUES ('$iname', $category, 'standard', $pprice, $sprice, $unit, $qty, $pprice, $sprice, '$newfilename', '$status', '$barcode', '$rdate',
    '$date', 1, $userid)";
    
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if($result){
        echo 'success';
    } else {
        echo 'failed';
    }
    exit;
}


if(isset($_POST['del_item'])){
    // Check if user has delete permission for items
    secureAjaxEndpoint($con, 'delete', 9); // Submenu ID 9 for items/list
    
    // Validate ID
    if(empty($_POST['del_item'])){
        echo "invalid_id";
        exit;
    }
    
    $id = intval($_POST['del_item']);
    
    if($id <= 0){
        echo "invalid_id";
        exit;
    }

    // Get item details
    $sql = "SELECT * FROM item WHERE item_id = $id";
    $res = mysqli_query($con, $sql);
    
    if(!$res){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($res) == 0){
        echo "item_not_found";
        exit;
    }
    
    $ro = mysqli_fetch_assoc($res);
    $filename = '../assets/images/products/' . $ro['item_image'];

    // Try to delete image file if exists
    if(!empty($ro['item_image']) && file_exists($filename)){
        if(!unlink($filename)){
            error_log("Failed to delete item image: " . $filename);
            // Continue with deletion even if image deletion fails
        }
    }
    
    // Delete item from database
    $stmt = "DELETE FROM item WHERE item_id = $id";
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if($result){
        echo 'deleted';
    } else {
        echo 'failed';
    }
    exit;
}

if(isset($_POST['getItems'])){
    // Check if user has view permission for items
    secureAjaxEndpoint($con, 'view', 9); // Submenu ID 9 for items/list
    $i = 0;
    $stmt = "SELECT item.*, unit.*, item_category.* from item, unit, item_category WHERE item.unit = unit.unit_id AND item.item_category = item_category.itemcat_id ORDER BY item.item_id DESC";
    $result = mysqli_query($con, $stmt);
    if(mysqli_num_rows($result) < 0){
      echo "
        <tr>
          <td colspan='7'> <center>No data available in table</center> </td>
        </tr>
      ";
    }else{
      while($row = mysqli_fetch_assoc($result)){
        $i++;
        $id= $row['item_id'];
        $iname = $row['item_name'];
        $sprice = $row['sale_price'];
        $pprice = $row['pur_price'];
        $image = $row['item_image'];
        $uname = $row['unit_name'];
        $qty = $row['qty'];
        $sname = $row['shortname'];
        $catname = $row['category_name'];
        $barcode = $row['barcode'];

        if($image != ""){ $image = "../assets/images/products/$image"; }else{
          $image  = "../assets/images/no-image.png";
        }

        echo "
          <tr>
            <td>$i</td>
            <td> <img src='$image' alt='' height='50'> $iname</td>
            <td>$catname</td>
            <td><span class='badge badge-info'>$barcode</span></td>
            <td>$sprice</td>
            <td>$pprice</td>
            <td>$qty $sname</td>
            <td>
              <div class='btn-group btn-group-toggle' data-toggle='buttons'>
                <button type='button' class='btn btn-info btn-sm'  data-toggle='modal' data-target='#itemDetailModal' data-toggle='tooltip' data-placement='top' title='View item detail' id='view_item' data-id='$id'> <i class='fa fa-eye'></i> </button>
                <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editItemModal' data-toggle='tooltip' data-placement='top' title='Edit item' id='edit_item' data-id='$id'> <i class='fa fa-edit'></i> </button>
                <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete customer' id='del_item' data-id='$id'> <i class='fa fa-trash'></i></button>
              </div>
            </td>
          </tr>
        ";
      }
    }
}

if(isset($_POST['viewSingleItem'])){
    // Check if user has view permission for items
    secureAjaxEndpoint($con, 'view', 9); // Submenu ID 9 for items/list
    $id = $_POST['viewSingleItem'];
    $stmt = "SELECT item.*, unit.*, item_category.*, users.fullname from item, unit, item_category, users WHERE item.unit = unit.unit_id AND
     item.item_category = item_category.itemcat_id AND item.reg_by = users.userid AND item.item_id = $id";
     $result = mysqli_query($con, $stmt);
     while($row = mysqli_fetch_assoc($result)){
        $iname = $row['item_name'];
        $sprice = $row['sale_price'];
        $pprice = $row['pur_price'];
        $image = $row['item_image'];
        $uname = $row['unit_name'];
        $qty = $row['qty'];
        $sname = $row['shortname'];
        $catname = $row['category_name'];
        $username = $row['fullname'];
        $barcode = $row['barcode'];
        $rgdate = date("M d, Y", strtotime($row['reg_date']));
        $rcdate = date("M d, Y", strtotime($row['recived_date']));
        $rtime = date("h:i:s", strtotime($row['recived_date']));
        

        if($image != ""){ $image = "../assets/images/products/$image"; }else{
            $image  = "../assets/images/no-image.png";
          }

        echo "
        <div class='row'>
        <div class='col-md-12'>
          <img src='$image' width='100' height='100' style='border:1px solid black; border-radius:4px;'>
           
         </div>

         <div class='row container my-4'>
           <div class='form-group col-md-4'>
             <label for=''> <strong>Item name</strong> </label>
             <p>$iname</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Category</strong> </label>
             <p>$catname</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Barcode</strong> </label>
             <p><span class='badge badge-info'>$barcode</span></p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Sell price</strong> </label>
             <p>$sprice $</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Purchase price</strong> </label>
             <p>$pprice $</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Current stock</strong> </label>
             <p>$qty $sname</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Unit</strong> </label>
             <p>$uname ($sname)</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Recieved date</strong> </label>
             <p>$rcdate</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Registered date</strong> </label>
             <p>$rgdate @ $rtime</p>
           </div>

           <div class='form-group col-md-4'>
             <label for=''> <strong>Registered by</strong> </label>
             <p>$username</p>
           </div>


         </div>
         
      </div>
        ";
     }
}

if(isset($_POST['editSingleItem'])){
    // Check if user has edit permission for items
    secureAjaxEndpoint($con, 'edit', 9); // Submenu ID 9 for items/list
    $id = $_POST['editSingleItem'];

    function get_categoreis($id){
        global $con; 
        $stmt = "SELECT * FROM item_category WHERE itemcat_id = $id";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['itemcat_id'];
            $name = $row['category_name'];

            echo "
                <option data-tokens='$name' value='$id'>$name  </option>
            ";
        }

        $stmt = "SELECT * FROM item_category WHERE itemcat_id != $id";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['itemcat_id'];
            $name = $row['category_name'];

            echo "
                <option data-tokens='$name' value='$id'>$name  </option>
            ";
        }

    }

    function get_unit($id){
        global $con; 
        $stmt = "SELECT * FROM unit WHERE unit_id = $id";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['unit_id'];
            $uname = $row['unit_name'];
            $sname = $row['shortname'];
            echo "
                <option data-tokens='$sname' value='$id' data-id='$sname'> $uname  </option>
            ";
        }

        $stmt = "SELECT * FROM unit WHERE unit_id != $id";
        $result = mysqli_query($con, $stmt);
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['unit_id'];
            $uname = $row['unit_name'];
            $sname = $row['shortname'];
            echo "
                <option data-tokens='$sname' value='$id' data-id='$sname'> $uname  </option>
            ";
        }

    }

    $stmt = "SELECT item.*, unit.*, item_category.* from item, unit, item_category WHERE item.unit = unit.unit_id AND 
    item.item_category = item_category.itemcat_id AND item.item_id = $id";
    $result = mysqli_query($con, $stmt);
    while($row  = mysqli_fetch_assoc($result)){
        $id= $row['item_id'];
        $iname = $row['item_name'];
        $sprice = $row['sale_price'];
        $pprice = $row['pur_price'];
        $image = $row['item_image'];
        $uname = $row['unit_name'];
        $qty = $row['qty'];
        $sname = $row['shortname'];
        $catname = $row['category_name'];
        $cat_id = $row['itemcat_id'];
        $unit_id = $row['unit_id'];
        $rdate = $row['recived_date'];
        $rdate = date("Y-m-d", strtotime($row['recived_date']));
        $barcode = $row['barcode'];


        echo "
        <div class='form-group col-md-4'>
        <label for=''> Item name *</label>
        <input type='hidden' name='item_id1' value='$id' id='item_id'>
        <div class='has-clearable'>
          <button type='button' class='close' aria-label='Close'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span></button>
          <input type='text' name='iname1' id='iname1' value='$iname' class='form-control rounded-0' autocomplete='off' required placeholder='Enter item name'>
        </div>
      </div>

      <div class='form-group col-md-4'>
        <label for=''>Category *</label>
        <select id='bss4 category' name='category1' class='form-control' data-toggle='selectpicker' required data-live-search='true' data-width='100%'>
        ";
        get_categoreis($cat_id);
         
      echo "</select>
      </div>
      <div class='form-group col-md-4'>
        <label class='control-label' for='bss4'>Unit *</label> 
        <select id='bss4' class='form-control'  name = 'unit1' data-toggle='selectpicker' data-live-search='true' data-width='100%' required>
       ";
       get_unit($unit_id);

        echo "</select>
      </div>

      <div class='form-group col-md-4'>
        <label for=''> Quantity *</label>
        <div class='input-group input-group-alt rounded-0'>
          <input type='number' min='1' value='$qty' class='form-control rounded-0' id='pi3' name='qty1' placeholder='Enter quantity' required>
          <div class='input-group-prepend'>
            <span class='input-group-text d-none ' id='qty_postfix'></span>
          </div>
        </div>
        
      </div>

      <div class='form-group col-md-4'>
        <label for=''> Purchase price *</label>
        <div class='input-group rounded-0'>
        <label class='input-group-prepend' for='pi9'><span class='badge'>$</span></label> <input type='number' name='pprice1' value='$pprice' placeholder='Enter purchase price' step='0.01' required class='form-control rounded-0' id='pi9'>
        </div>
        
      </div>

      <div class='form-group col-md-4'>
        <label for=''> Sale price *</label>
        <div class='input-group rounded-0'>
          <label class='input-group-prepend' for='pi9'><span class='badge'>$</span></label> <input type='number' name='sprice1' value='$sprice' placeholder='Enter Sale price' step='0.01' required class='form-control rounded-0' id='pi9'>
        </div>
      </div>

      <div class='form-group col-md-4'>
        <label class='control-label' for='flatpickr03'>Recieved date</label> 
       <input type='date' name='rdate1' value='$rdate' id='' class='form-control'>
      </div>

      <div class='form-group col-md-4'>
        <label for=''>Barcode</label>
        <div class='has-clearable'>
          <button type='button' class='close' aria-label='Close'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span></button>
          <input type='text' name='barcode1' id='barcode1' value='$barcode' class='form-control rounded-0' autocomplete='off' placeholder='Enter barcode'>
        </div>
      </div>

      <div class='form-group col-md-8'>
        <label for=''>Item image</label>
        <input type='file' name='iimage1' id='iimage1' class='form-control'>
      </div>


      <div class='form-group col-md-12'>
        <button type='submit' class='btn btn-info rounded-0'> Update item</button>
        <button type='reset' class='btn btn-danger rounded-0'> <i class='fa fa-reload'></i> Reset</button>
      </div>
        ";
    }
}

if(isset($_POST['item_id1'])){
    // Check if user has edit permission for items
    secureAjaxEndpoint($con, 'edit', 9); // Submenu ID 9 for items/list
    
    // Validate required fields
    if(empty($_POST['iname1']) || empty($_POST['category1']) || empty($_POST['unit1']) || empty($_POST['item_id1'])){
        echo "missing_fields";
        exit;
    }
    
    $iname = mysqli_real_escape_string($con, $_POST['iname1']);
    $category = intval($_POST['category1']);
    $unit = intval($_POST['unit1']);
    $qty = floatval($_POST['qty1']);
    $pprice = floatval($_POST['pprice1']);
    $sprice = floatval($_POST['sprice1']);
    $rdate = mysqli_real_escape_string($con, $_POST['rdate1']);
    $id = intval($_POST['item_id1']);
    $barcode1 = mysqli_real_escape_string($con, $_POST['barcode1']);
    
    // Validate numeric values
    if($qty < 0 || $pprice < 0 || $sprice < 0){
        echo "invalid_values";
        exit;
    }
    
    // Handle file upload
    $iimage = "";
    $has_new_image = false;
    if(!empty($_FILES["iimage1"]["name"])){
        $temp = explode(".", $_FILES["iimage1"]["name"]);
        $iimage = rand() . date('m-d-y-h-i-s') . '.' . end($temp);
        
        // Check if upload was successful
        if(move_uploaded_file($_FILES["iimage1"]["tmp_name"], "../assets/images/products/" . $iimage)){
            $has_new_image = true;
        } else {
            error_log("Failed to upload image: " . $_FILES["iimage1"]["error"]);
            $iimage = "";
        }
    }

    // Get current item data
    $sql = "SELECT * FROM item WHERE item_id = $id";
    $res = mysqli_query($con, $sql);
    
    if(!$res){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if(mysqli_num_rows($res) == 0){
        echo "item_not_found";
        exit;
    }
    
    $ro = mysqli_fetch_assoc($res);
    $curr_iname = $ro['item_name'];
    $curr_barcode = $ro['barcode'];

    // Check for duplicate barcode if barcode is being changed
    if($curr_barcode != $barcode1){
        $stmt = "SELECT * FROM item WHERE barcode = '$barcode1' AND item_id != $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo "database_error: " . mysqli_error($con);
            exit;
        }
        
        if(mysqli_num_rows($result) > 0){
            echo "found_barcode";
            exit;
        }
    }

    // Check for duplicate item name if name is being changed
    if($curr_iname != $iname){
        $stmt = "SELECT * FROM item WHERE item_name = '$iname' AND item_id != $id";
        $result = mysqli_query($con, $stmt);
        
        if(!$result){
            echo "database_error: " . mysqli_error($con);
            exit;
        }
        
        if(mysqli_num_rows($result) > 0){
            echo "found_iname";
            exit;
        }
    }
    
    // Prepare UPDATE query
    if($has_new_image){
        $stmt = "UPDATE item SET item_name = '$iname', item_category = $category, item_cost = $pprice, item_price = $sprice, unit = $unit, qty = $qty, 
        pur_price = $pprice, sale_price = $sprice, recived_date = '$rdate', item_image = '$iimage', barcode = '$barcode1'
        WHERE item_id = $id";
    } else {
        $stmt = "UPDATE item SET item_name = '$iname', item_category = $category, item_cost = $pprice, item_price = $sprice, unit = $unit, qty = $qty, 
        pur_price = $pprice, sale_price = $sprice, recived_date = '$rdate', barcode = '$barcode1'
        WHERE item_id = $id";
    }
    
    $result = mysqli_query($con, $stmt);
    
    if(!$result){
        echo "database_error: " . mysqli_error($con);
        exit;
    }
    
    if($result){
        echo "updated";
    } else {
        echo "failed";
    }
    exit;
}


?>
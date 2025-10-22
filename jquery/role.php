<?php 
session_start();

include('../inc/config.php');

date_default_timezone_set('Africa/Nairobi');

if(isset($_POST['rname'])){
    $name = mysqli_real_escape_string($con, $_POST['rname']);
    $desc = mysqli_real_escape_string($con, $_POST['desc']);

    $userid = $_SESSION['uid'];
    $date = date('Y-m-d h:i:s a');

    ///Cheking if category is registered
    $stmt = "SELECT * FROM usergroup WHERE group_name = '$name' ";
    $result = mysqli_query($con, $stmt); 
    if(mysqli_num_rows($result) > 0){
        echo "found_role";
    }else{
      
        $stmt = "INSERT INTO usergroup (group_name, description,warehouse, created_date, created_by) 
        VALUES ('$name', '$desc', 1, '$date', 1)";
        $result = mysqli_query($con, $stmt); 
        if($result){
            echo 'success';
        }
        
    }

}

if(isset($_POST['all_unit'])){
    $i = 0;
    $stmt = "SELECT * FROM unit ORDER BY unit_id DESC";
    $result = mysqli_query($con, $stmt);
    while($row = mysqli_fetch_assoc($result)){
      $i++;
      $id = $row['unit_id'];
      $name = $row['unit_name'];
      $des = $row['shortname'];
      $date = date("M d, Y", strtotime($row['reg_date']));
      if($des == "" ){ $des = "N/A"; }

      echo "
        <tr>
          <td>$i</td>
          <td>$name</td>
          <td>$des</td>
          <td>$date</td>
          <td>
            <div class='btn-group btn-group-toggle' data-toggle='buttons'>
              <button type='button' class='btn btn-warning btn-sm'  data-toggle='tooltip' data-placement='top' title='Edit Category' id='edit_unit' data-id='$id'> <i class='fa fa-edit'></i> </button>
              <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete category' id='del_unit' data-id='$id'> <i class='fa fa-trash'></i></button>
            </div>
          </td>
        </tr>
      ";

    }
}


if(isset($_POST['del_role'])){
    $id = $_POST['del_role'];

    $stmt = "DELETE FROM usergroup WHERE group_id = $id";
    $result = mysqli_query($con, $stmt);
    if($result){
        echo 'deleted';
    }
}

if(isset($_POST['edit_role'])){
    $id = $_POST['edit_role'];

    $stmt = "SELECT * FROM usergroup WHERE group_id = $id";
    $result = mysqli_query($con, $stmt);
    if($row = mysqli_fetch_assoc($result)){
        $id = $row['group_id'];
        $name = $row['group_name'];
        $sname = $row['description'];

        echo "
        <div class='form-group col-md-12'>
            <label for=''> Role name *</label>
            <input type='hidden' name='upd_role' id='upd_role' value='$id'>
            <input type='text' name='rname1' id='rname1' value='$name' minlength='5' class='form-control rounded-0' autocomplete='off' required>
        </div>

      <div class='form-group col-md-12'>
        <label for=''> Description </label>
        <textarea name='desc1' id='desc1' class='form-control'>$sname</textarea>
      </div>

      <div class='form-group col-md-6'>
        <button type='submit' class='btn btn-success'> <i class='fa fa-edit'></i> Update role</button>
      </div>
        ";
    }
}


if(isset($_POST['upd_role'])){
    $id = $_POST['upd_role'];
    $uname = mysqli_real_escape_string($con, $_POST['rname1']);
    $sname = mysqli_real_escape_string($con, $_POST['desc1']);


    $sql = "SELECT * FROM usergroup WHERE group_id = $id";
    $re = mysqli_query($con, $sql);
    $ro = mysqli_fetch_assoc($re);
    $curr_role = $ro['group_name'];

    if($curr_role != $uname){
        $stmt = "SELECT * FROM usergroup WHERE group_name = '$uname'";
        $result = mysqli_query($con, $stmt);
        if(mysqli_num_rows($result) > 0){
            echo "found_role";
        }else{
        
          
            $stmt = "UPDATE usergroup SET group_name = '$uname', description = '$sname' WHERE group_id = $id";
            $result = mysqli_query($con, $stmt);

            if($result){
                echo 'success';
            }
            
        }
    }else{
            $stmt = "UPDATE usergroup SET group_name = '$uname', description = '$sname' WHERE group_id = $id";
        $result = mysqli_query($con, $stmt);
        if($result){
            echo 'success';
        }
    }

  
}


?>
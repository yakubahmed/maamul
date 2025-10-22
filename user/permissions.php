<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Permissions Management'; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Users'  ?>
      
      <?php $smenu = 'Permissions'  ?>
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
                  <h1 class="page-title"> Permissions Management </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-info text-right" data-toggle="modal" data-target="#addPermissionModal"> <i class="fa fa-plus"></i> Add Permission </button>
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
                        <div class="row mb-3">
                          <div class="col-md-4">
                            <label for="groupFilter">Filter by Group:</label>
                            <select class="form-control" id="groupFilter">
                              <option value="">All Groups</option>
                              <?php 
                                $stmt = "SELECT * FROM usergroup ORDER BY group_name ASC";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  echo "<option value='{$row['group_id']}'>{$row['group_name']}</option>";
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Group</th>
                              <th>Menu Item</th>
                              <th>View</th>
                              <th>Add</th>
                              <th>Edit</th>
                              <th>Delete</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="permissionsTable">                               
                               <?php
                                $i = 0;
                                $stmt = "SELECT p.*, ug.group_name, sm.sub_menu_name 
                                         FROM previlage p 
                                         LEFT JOIN usergroup ug ON p.group_id = ug.group_id 
                                         LEFT JOIN submenu sm ON p.sub_menu_id = sm.submenu_id 
                                         ORDER BY ug.group_name ASC, sm.sub_menu_name ASC";
                                $result = mysqli_query($con, $stmt);
                                if(mysqli_num_rows($result) > 0){
                                  while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['prev_id'];
                                    $group_name = $row['group_name'];
                                    $menu_name = $row['sub_menu_name'];
                                    $view = $row['view'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';
                                    $add = $row['add'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';
                                    $edit = $row['edit'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';
                                    $delete = $row['delete'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';

                                    echo "
                                      <tr data-group='{$row['group_id']}'>
                                        <td>$i</td>
                                        <td>$group_name</td>
                                        <td>$menu_name</td>
                                        <td>$view</td>
                                        <td>$add</td>
                                        <td>$edit</td>
                                        <td>$delete</td>
                                        <td>
                                          <div class='btn-group btn-group-toggle' data-toggle='buttons'>
                                            <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editPermissionModal' data-toggle='tooltip' data-placement='top' title='Edit permission' id='edit_permission' data-id='$id'> <i class='fa fa-edit'></i> </button>
                                            <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete permission' id='del_permission' data-id='$id'> <i class='fa fa-trash'></i></button>
                                          </div>
                                        </td>
                                      </tr>
                                    ";
                                  }
                                } else {
                                  echo "
                                    <tr>
                                      <td colspan='8'> <center>No permissions found</center> </td>
                                    </tr>
                                  ";
                                }
                               
                               ?>
                          </tbody>

                        </table>
              
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->
             
                <!-- Add Permission Modal -->
                <div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="addPermissionModalLabel">Add New Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="addPermissionForm">
                          <div class="form-group">
                            <label for="permission_group">Group *</label>
                            <select class="form-control" id="permission_group" name="group_id" required>
                              <option value="">Select Group</option>
                              <?php 
                                $stmt = "SELECT * FROM usergroup ORDER BY group_name ASC";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  echo "<option value='{$row['group_id']}'>{$row['group_name']}</option>";
                                }
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="permission_menu">Menu Item *</label>
                            <select class="form-control" id="permission_menu" name="sub_menu_id" required>
                              <option value="">Select Menu Item</option>
                              <?php 
                                $stmt = "SELECT * FROM submenu ORDER BY sub_menu_name ASC";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  echo "<option value='{$row['submenu_id']}'>{$row['sub_menu_name']}</option>";
                                }
                              ?>
                            </select>
                          </div>
                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permission_view" name="view" checked>
                                <label class="form-check-label" for="permission_view">View</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permission_add" name="add" checked>
                                <label class="form-check-label" for="permission_add">Add</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permission_edit" name="edit" checked>
                                <label class="form-check-label" for="permission_edit">Edit</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="permission_delete" name="delete" checked>
                                <label class="form-check-label" for="permission_delete">Delete</label>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="savePermission">Save Permission</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Edit Permission Modal -->
                <div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editPermissionModalLabel">Edit Permission</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="editPermissionForm">
                          <input type="hidden" id="edit_permission_id" name="prev_id">
                          <div class="form-group">
                            <label for="edit_permission_group">Group *</label>
                            <select class="form-control" id="edit_permission_group" name="group_id" required>
                              <option value="">Select Group</option>
                              <?php 
                                $stmt = "SELECT * FROM usergroup ORDER BY group_name ASC";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  echo "<option value='{$row['group_id']}'>{$row['group_name']}</option>";
                                }
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="edit_permission_menu">Menu Item *</label>
                            <select class="form-control" id="edit_permission_menu" name="sub_menu_id" required>
                              <option value="">Select Menu Item</option>
                              <?php 
                                $stmt = "SELECT * FROM submenu ORDER BY sub_menu_name ASC";
                                $result = mysqli_query($con, $stmt);
                                while($row = mysqli_fetch_assoc($result)){
                                  echo "<option value='{$row['submenu_id']}'>{$row['sub_menu_name']}</option>";
                                }
                              ?>
                            </select>
                          </div>
                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_permission_view" name="view">
                                <label class="form-check-label" for="edit_permission_view">View</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_permission_add" name="add">
                                <label class="form-check-label" for="edit_permission_add">Add</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_permission_edit" name="edit">
                                <label class="form-check-label" for="edit_permission_edit">Edit</label>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_permission_delete" name="delete">
                                <label class="form-check-label" for="edit_permission_delete">Delete</label>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="updatePermission">Update Permission</button>
                      </div>
                    </div>
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
<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

  // Filter by group
  $('#groupFilter').change(function(){
    var groupId = $(this).val();
    if(groupId === ''){
      $('tbody tr').show();
    } else {
      $('tbody tr').hide();
      $('tbody tr[data-group="' + groupId + '"]').show();
    }
  });

  // Save new permission
  $('#savePermission').click(function(){
    var groupId = $('#permission_group').val();
    var menuId = $('#permission_menu').val();
    var view = $('#permission_view').is(':checked') ? 1 : 0;
    var add = $('#permission_add').is(':checked') ? 1 : 0;
    var edit = $('#permission_edit').is(':checked') ? 1 : 0;
    var delete_perm = $('#permission_delete').is(':checked') ? 1 : 0;
    
    if(groupId === '' || menuId === ''){
      toastr.error("Please select both group and menu item!");
      return;
    }
    
    $.ajax({
      url: '../jquery/user-permissions.php',
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'add_permission',
        group_id: groupId,
        sub_menu_id: menuId,
        view: view,
        add: add,
        edit: edit,
        delete: delete_perm
      },
      success: function(response){
        if(response.success === true){
          toastr.success("Permission added successfully!");
          $('#addPermissionModal').modal('hide');
          $('#addPermissionForm')[0].reset();
          location.reload();
        } else if(response.error === 'exists'){
          toastr.error("Permission already exists for this group and menu item!");
        } else if(response.error === 'empty_fields'){
          toastr.error("Please select both group and menu item!");
        } else if(response.error === 'not_logged_in'){
          toastr.error("Session expired. Please login again.");
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        } else {
          toastr.error("Error adding permission!");
        }
      },
      error: function(xhr, status, error){
        console.log('AJAX Error:', error);
        console.log('Response:', xhr.responseText);
        toastr.error("Network error. Please try again.");
      }
    });
  });

  // Edit permission
  $(document).on('click', '#edit_permission', function(){
    var permissionId = $(this).data('id');
    
    $.ajax({
      url: '../jquery/user-permissions.php',
      type: 'POST',
      data: {action: 'get_permission', prev_id: permissionId},
      success: function(response){
        var permission = JSON.parse(response);
        $('#edit_permission_id').val(permission.prev_id);
        $('#edit_permission_group').val(permission.group_id);
        $('#edit_permission_menu').val(permission.sub_menu_id);
        $('#edit_permission_view').prop('checked', permission.view == 1);
        $('#edit_permission_add').prop('checked', permission.add == 1);
        $('#edit_permission_edit').prop('checked', permission.edit == 1);
        $('#edit_permission_delete').prop('checked', permission.delete == 1);
      }
    });
  });

  // Update permission
  $('#updatePermission').click(function(){
    var permissionId = $('#edit_permission_id').val();
    var groupId = $('#edit_permission_group').val();
    var menuId = $('#edit_permission_menu').val();
    var view = $('#edit_permission_view').is(':checked') ? 1 : 0;
    var add = $('#edit_permission_add').is(':checked') ? 1 : 0;
    var edit = $('#edit_permission_edit').is(':checked') ? 1 : 0;
    var delete_perm = $('#edit_permission_delete').is(':checked') ? 1 : 0;
    
    if(groupId === '' || menuId === ''){
      toastr.error("Please select both group and menu item!");
      return;
    }
    
    $.ajax({
      url: '../jquery/user-permissions.php',
      type: 'POST',
      data: {
        action: 'update_permission',
        prev_id: permissionId,
        group_id: groupId,
        sub_menu_id: menuId,
        view: view,
        add: add,
        edit: edit,
        delete: delete_perm
      },
      success: function(response){
        if(response === 'success'){
          toastr.success("Permission updated successfully!");
          $('#editPermissionModal').modal('hide');
          location.reload();
        } else if(response === 'exists'){
          toastr.error("Permission already exists for this group and menu item!");
        } else {
          toastr.error("Error updating permission!");
        }
      }
    });
  });

  // Delete permission
  $(document).on('click', '#del_permission', function(){
    var permissionId = $(this).data('id');
    
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: '../jquery/user-permissions.php',
          type: 'POST',
          data: {action: 'delete_permission', prev_id: permissionId},
          success: function(response){
            if(response === 'success'){
              Swal.fire('Deleted!', 'Permission deleted successfully.', 'success');
              location.reload();
            } else {
              Swal.fire('Error!', 'Error deleting permission.', 'error');
            }
          }
        });
      }
    });
  });

})

</script>

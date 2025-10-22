<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'User Groups'; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Users'  ?>
      
      <?php $smenu = 'User Groups'  ?>
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
                  <h1 class="page-title"> User Groups </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-info text-right" data-toggle="modal" data-target="#addGroupModal"> <i class="fa fa-plus"></i> Add Group </button>
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
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Group Name</th>
                              <th>Description</th>
                              <th>Created Date</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="groupsTable">                               
                               <?php
                                $i = 0;
                                $stmt = "SELECT * FROM usergroup ORDER BY group_id DESC";
                                $result = mysqli_query($con, $stmt);
                                if(mysqli_num_rows($result) > 0){
                                  while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['group_id'];
                                    $name = $row['group_name'];
                                    $desc = $row['description'];
                                    $date = date("M d, Y", strtotime($row['created_date']));

                                    echo "
                                      <tr>
                                        <td>$i</td>
                                        <td>$name</td>
                                        <td>$desc</td>
                                        <td>$date</td>
                                        <td>
                                          <div class='btn-group btn-group-toggle' data-toggle='buttons'>
                                            <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editGroupModal' data-toggle='tooltip' data-placement='top' title='Edit group' id='edit_group' data-id='$id'> <i class='fa fa-edit'></i> </button>
                                            <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#permissionsModal' data-toggle='tooltip' data-placement='top' title='Manage permissions' id='manage_permissions' data-id='$id'> <i class='fa fa-key'></i> </button>
                                            <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete group' id='del_group' data-id='$id'> <i class='fa fa-trash'></i></button>
                                          </div>
                                        </td>
                                      </tr>
                                    ";
                                  }
                                } else {
                                  echo "
                                    <tr>
                                      <td colspan='5'> <center>No groups found</center> </td>
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
             
                <!-- Add Group Modal -->
                <div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="addGroupModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="addGroupModalLabel">Add New Group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="addGroupForm">
                          <div class="form-group">
                            <label for="group_name">Group Name *</label>
                            <input type="text" class="form-control" id="group_name" name="group_name" required>
                          </div>
                          <div class="form-group">
                            <label for="group_description">Description</label>
                            <textarea class="form-control" id="group_description" name="group_description" rows="3"></textarea>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveGroup">Save Group</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Edit Group Modal -->
                <div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-labelledby="editGroupModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editGroupModalLabel">Edit Group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="editGroupForm">
                          <input type="hidden" id="edit_group_id" name="group_id">
                          <div class="form-group">
                            <label for="edit_group_name">Group Name *</label>
                            <input type="text" class="form-control" id="edit_group_name" name="group_name" required>
                          </div>
                          <div class="form-group">
                            <label for="edit_group_description">Description</label>
                            <textarea class="form-control" id="edit_group_description" name="group_description" rows="3"></textarea>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="updateGroup">Update Group</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Permissions Modal -->
                <div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="permissionsModalLabel">Manage Permissions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="permissionsContent">
                          <!-- Permissions will be loaded here -->
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="savePermissions">Save Permissions</button>
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

  // Save new group
  $('#saveGroup').click(function(){
    var groupName = $('#group_name').val();
    var groupDesc = $('#group_description').val();
    
    if(groupName.trim() === ''){
      toastr.error("Group name is required!");
      return;
    }
    
    $.ajax({
      url: '../jquery/user-groups.php',
      type: 'POST',
      data: {
        action: 'add_group',
        group_name: groupName,
        group_description: groupDesc
      },
      success: function(response){
        if(response === 'success'){
          toastr.success("Group added successfully!");
          $('#addGroupModal').modal('hide');
          $('#addGroupForm')[0].reset();
          location.reload();
        } else if(response === 'exists'){
          toastr.error("Group name already exists!");
        } else {
          toastr.error("Error adding group!");
        }
      }
    });
  });

  // Edit group
  $(document).on('click', '#edit_group', function(){
    var groupId = $(this).data('id');
    
    $.ajax({
      url: '../jquery/user-groups.php',
      type: 'POST',
      data: {action: 'get_group', group_id: groupId},
      success: function(response){
        var group = JSON.parse(response);
        $('#edit_group_id').val(group.group_id);
        $('#edit_group_name').val(group.group_name);
        $('#edit_group_description').val(group.description);
      }
    });
  });

  // Update group
  $('#updateGroup').click(function(){
    var groupId = $('#edit_group_id').val();
    var groupName = $('#edit_group_name').val();
    var groupDesc = $('#edit_group_description').val();
    
    if(groupName.trim() === ''){
      toastr.error("Group name is required!");
      return;
    }
    
    $.ajax({
      url: '../jquery/user-groups.php',
      type: 'POST',
      data: {
        action: 'update_group',
        group_id: groupId,
        group_name: groupName,
        group_description: groupDesc
      },
      success: function(response){
        if(response === 'success'){
          toastr.success("Group updated successfully!");
          $('#editGroupModal').modal('hide');
          location.reload();
        } else if(response === 'exists'){
          toastr.error("Group name already exists!");
        } else {
          toastr.error("Error updating group!");
        }
      }
    });
  });

  // Delete group
  $(document).on('click', '#del_group', function(){
    var groupId = $(this).data('id');
    
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
          url: '../jquery/user-groups.php',
          type: 'POST',
          data: {action: 'delete_group', group_id: groupId},
          success: function(response){
            if(response === 'success'){
              Swal.fire('Deleted!', 'Group deleted successfully.', 'success');
              location.reload();
            } else if(response === 'in_use'){
              Swal.fire('Error!', 'Cannot delete group. It is being used by users.', 'error');
            } else {
              Swal.fire('Error!', 'Error deleting group.', 'error');
            }
          }
        });
      }
    });
  });

  // Manage permissions
  $(document).on('click', '#manage_permissions', function(){
    var groupId = $(this).data('id');
    
    $.ajax({
      url: '../jquery/user-groups.php',
      type: 'POST',
      data: {action: 'get_permissions', group_id: groupId},
      success: function(response){
        $('#permissionsContent').html(response);
        $('#permissionsModal').data('group-id', groupId);
      }
    });
  });

  // Save permissions
  $('#savePermissions').click(function(){
    var groupId = $('#permissionsModal').data('group-id');
    var permissions = [];
    
    $('.permission-checkbox:checked').each(function(){
      permissions.push($(this).val());
    });
    
    $.ajax({
      url: '../jquery/user-groups.php',
      type: 'POST',
      data: {
        action: 'save_permissions',
        group_id: groupId,
        permissions: permissions
      },
      success: function(response){
        if(response === 'success'){
          toastr.success("Permissions saved successfully!");
          $('#permissionsModal').modal('hide');
        } else {
          toastr.error("Error saving permissions!");
        }
      }
    });
  });

  // Select all permissions
  $(document).on('click', '#selectAll', function(){
    $('.permission-checkbox').prop('checked', true);
  });

  // Deselect all permissions
  $(document).on('click', '#deselectAll', function(){
    $('.permission-checkbox').prop('checked', false);
  });

})

</script>

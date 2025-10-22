<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Stock Adjustment';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Stock'  ?>
      
      <?php $smenu = 'Stock Adjustment'  ?>
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
                  <h1 class="page-title"> Stock Adjustment </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button  class="btn btn-info text-right" data-toggle="modal" data-target="#addAdjModal"> <i class="fa fa-plus"></i> Add new adjustment </button>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">
                      
                      <div class="card-body ">
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Item</th>
                              <th>Quantity</th>
                              <th>Date</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php 
                                $i = 0;
                                $stmt = "SELECT item.item_name, stock_adjustment.* FROM item, stock_adjustment
                                WHERE item.item_id = stock_adjustment.item_id ORDER BY sa_id DESC" ;
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['sa_id'];
                                    $name = $row['item_name'];
                                    $adj = $row['adju_type'];
                                    $qty = $row['quantity'];
                                    $date = date("M d, Y", strtotime($row['date']));

                                    if($adj == "Add"){
                                      $adj = " <span class='text-success'> <strong> + $qty </strong> </span> ";
                                    }else if($adj == "Subtract"){
                                      $adj = " <span class='text-danger'> <strong> - $qty </strong> </span> ";

                                    }

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$name</td>
                                            <td>$adj</td>
                                            <td>$date </td>
                                            <td>
                                                <div class='btn-group btn-group-toggle' data-toggle='buttons'>
                                                <button type='button' class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editAdjModal' data-toggle='tooltip' data-placement='top' title='Edit Adjustment' id='edit_adju' data-id='$id'> <i class='fa fa-edit'></i> </button>
                                                <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Delete Adjustment' id='del_adju' data-id='$id'> <i class='fa fa-trash'></i></button>
                                                </div>

                                               
                                            </td>
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

                  <!-- Normal modal -->
                      <div class="modal fade" id="addAdjModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                              <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalCenterLabel" class="modal-title"> Add Adjustment </h5>
                            </div><!-- /.modal-header -->
                            <form  method="post" id='frmAddAdj'>
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="row">
                                  <div class="form-group col-md-12">
                                    <label for="">Item *</label>
                                    <select name="item" id="bss3" class="form-control customer" required data-toggle='selectpicker' required data-live-search='true' >
                                      <option data-tokens='' value=''>Select item  </option>
                                    
                                      <?php
                                        $stmt = "SELECT * FROM item ";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                          $id = $row['item_id'];
                                          $name = $row['item_name'];
                                          echo "
                                            <option data-tokens='$id' value='$id'>$name  </option>
  
                                          ";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="">Current stock</label>
                                    <p id='curr_stock'>-</p>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="">Quantity *</label>
                                    <input type="number" min='1' name="qty" id="qty" required class="form-control">
                                  </div>

                                  <div class="form-group col-md-12">
                                    <label for="">Adjustment type *</label>
                                    <select name="adjtype" id="adjtype" class="form-control" required>
                                        <option value="">Select Adjustment type</option>
                                        <option value="Add">Add</option>
                                        <option value="Subtract">Subtract</option>
                                    </select>
                                  </div>

                                  <div class="form-group col-md-12">
                                    <label for="">Date *</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                  </div>


                                </div>
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Save adjustment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                              </div><!-- /.modal-footer -->
                            </form>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

                  <!-- Edit Modal -->
                      <div class="modal fade" id="editAdjModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                              <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalCenterLabel" class="modal-title"> Add Adjustment </h5>
                            </div><!-- /.modal-header -->
                            <form  method="post" id='' >
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="row" id='frmEditAdj'>
                           


                                </div>
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Update adjustment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                              </div><!-- /.modal-footer -->
                            </form>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
             
 

              </div><!-- /.page-section -->

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



      //Delete product
      $(document).on('click', '#del_adju', function(){
        var id = $(this).data('id');
        console.log(id)
        console.log(id)
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
                  url:'../jquery/stock-adjustment.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_adju='+id,
                  success: function(data){
                    if(typeof data === 'object'){
                      if(data.error === 'not_logged_in'){
                        Swal.fire(
                          'Session Expired!',
                          'You must be logged in to delete adjustments.',
                          'error'
                        ).then(() => {
                          window.location.href = '<?= BASE_URL ?>login.php'
                        })
                      }else if(data.success === true){
                        Swal.fire(
                          'Deleted!',
                          'Stock adjustment deleted successfully.',
                          'success'
                        )
                        location.reload();
                      }else{
                        Swal.fire(
                          'Error!',
                          'Something went wrong.',
                          'error'
                        )
                      }
                    }else{
                      // Handle legacy string responses
                      if(data == 'deleted'){
                        Swal.fire(
                          'Deleted!',
                          'Stock adjustment deleted successfully.',
                          'success'
                        )
                        location.reload();
                      }else{
                        Swal.fire(
                          'Error!',
                          'Something is wrong please try again.',
                          'error'
                        )
                      }
                    }
                  },
                  error: function(xhr, status, error){
                    Swal.fire(
                      'Network Error!',
                      'Please check your connection.',
                      'error'
                    )
                  }
                })

              }
            })
      })


      function get_customers(){
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: 'getCustomers=24',
            success: function(data){
                $('tbody').html(data)
            }
        });
      }

      // Displaying customer detail
      $(document).on('click', '#view_cust', function(){
        var id = $(this).data('id')
        $('.overlay').removeClass('d-none');
        $.ajax({
            url:'../jquery/customer.php',
            type:'post',
            data: 'viewSingleCust='+id,
            success: function(data){
                $('#custDetailDisp').html(data)
                $('.overlay').addClass('d-none');

            }
        });
      })

      // Edit customer - displaying information
      $(document).on('click', '#edit_adju', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/stock-adjustment.php',
            type:'post',
            data: 'editSingleAdj='+id,
            success: function(data){
                $('#frmEditAdj').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })

      //Updating Stock Adjustment
      $('#frmAddAdj').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/stock-adjustment.php', 
          type:'post', 
          dataType: 'json',
          data: $('#frmAddAdj').serialize(),
          success: function(res){
            // Handle JSON response for session expired
            if(typeof res === 'object' && res.error === 'Session expired'){
              toastr.error("Your session has expired. Please login again.")
              setTimeout(function(){
                window.location.href = '<?= BASE_URL ?>login.php'
              }, 2000);
              return;
            }
            
            // Handle JSON responses
            if(typeof res === 'object'){
              if(res.error === 'not_logged_in'){
                toastr.error("You must be logged in to make stock adjustments")
                window.location.href = '<?= BASE_URL ?>login.php'
              }else if(res.error === 'missing_fields'){
                toastr.error("Please fill in all required fields")
              }else if(res.error === 'invalid_quantity'){
                toastr.error("Please enter a valid positive quantity")
              }else if(res.error === 'invalid_adjustment_type'){
                toastr.error("Please select a valid adjustment type")
              }else if(res.success === true){
                toastr.success("Stock adjustment created successfully")
                $('#frmAddAdj')[0].reset();
                $('#frmAddAdj').modal('hide');
                setTimeout(() => {
                  location.reload()
                }, 500);
              }else{
                toastr.error("An unexpected error occurred. Please try again.")
                console.error('Unexpected response:', res)
              }
            }else{
              // Handle legacy string responses (fallback)
              if (res == 'success'){
                toastr.success("Stock adjustment created successfully")
                $('#frmAddAdj')[0].reset();
                $('#frmAddAdj').modal('hide');
                setTimeout(() => {
                  location.reload()
                }, 500);
              }else{
                toastr.error("Something is wrong please try again")
                console.log(res)
              }
            }
          },
          error: function(xhr, status, error){
            toastr.error("Network error occurred. Please check your connection.")
            console.error('AJAX Error:', error)
          }
        })
      });



      $(document).on('change', '#bss3', function(){
        var id = $(this).val()
        $.ajax({
            url:'../jquery/stock-adjustment.php',
            type:'post',
            data: 'curr_stock='+id,
            success: function(data){
                $('#curr_stock').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })


      $('#frmEditAdj').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/stock-adjustment.php', 
          type:'post', 
          data: $('#frmAddAdj').serialize(),
          success: function(res){
            if (res == 'success'){
              toastr.success("Customer registered successfully")
              $('#frmAddAdj')[0].reset();
              $('#frmAddAdj').modal('hide');
              setTimeout(() => {
                location.reload()
                
              }, 500);

            }else{
              toastr.error("Something is wrong please try again")
              console.log(res)
            }
          }
        })
      });



})

</script>
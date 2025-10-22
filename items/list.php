<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Item list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Alaab'  ?>
      
      <?php $smenu = 'Liiska alaabta'  ?>
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
                  <h1 class="page-title"> Item list </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>items/add" class="btn btn-info text-right"> <i class="fa fa-plus"></i> Add Item </a>
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
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Product</th>
                              <th>Category</th>
                              <th>Barcode</th>
                              <th>Sale price</th>
                              <th>Purchase price</th>
                              <th>Current stock</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>                               
                               <?php
                                $i = 0;
                                $stmt = "SELECT item.*, unit.*, item_category.* from item, unit, item_category WHERE item.unit = unit.unit_id AND item.item_category = item_category.itemcat_id ORDER BY item.item_id DESC";
                                $result = mysqli_query($con, $stmt);
                                if(mysqli_num_rows($result) < 0){
                                  echo "
                                    <tr>
                                      <td colspan='8'> <center>No data available in table</center> </td>
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
                                      $image  = "../assets/images/products/item_placeholder.png";
                                    }

                                    echo "
                                      <tr>
                                        <td>$i</td>
                                        <td> <img src='$image' alt='' height='30'> $iname</td>
                                        <td>$catname</td>
                                        <td><span class='badge badge-info'>$barcode</span></td>
                                        <td>$sprice</td>
                                        <td>$pprice</td>
                                        <td>$qty $sname</td>
                                        <td>
                                          <div class='dropdown d-inline-block'>
                                            <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                            <div class='dropdown-menu dropdown-menu-right' style=''>
                                              <div class='dropdown-arrow'></div>
                                              <button type='button' data-toggle='modal' data-target='#itemDetailModal' id='view_item' data-id='$id' class='dropdown-item'> <i class='fa fa-eye'></i> View Item</button> 
                                              <button type='button' data-toggle='modal' data-target='#editItemModal' id='edit_item' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Item</button>
                                              <button type='button' class='dropdown-item btn-danger' id='del_item' data-id='$id'><i class='fa fa-trash'></i> Delete Item</button>
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                    ";
                                  }
                                }
                               
                               ?>
                          </tbody>

                        </table>
              
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->
             
                <!-- Customer detail modal drawer -->
                <div class="modal modal-drawer fade" id="itemDetailModal" tabindex="-1" role="dialog" aria-labelledby="itemDetailModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header bg-info text-light">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Item Detail</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body my-4" id='itemDetailDesp'>


                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

              </div><!-- /.page-section -->

              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Edit item</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='editItemPlace row' method='post' id='frmEditItem' enctype='multipart/form-data' >
                                
                                </form>
                              </div>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

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
      $(document).on('click', '#del_item', function(){
        var id = $(this).data('id');
        console.log('Delete item clicked, ID:', id); // Debug log
        
        if(!id){
          toastr.error('Item ID not found');
          return;
        }
        
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
              url:'../jquery/add-item.php',
              type:'post',
              data: {del_item: id},
              success: function(data){
                data = data.trim(); // Trim whitespace
                console.log('Delete response:', data); // Debug log
                
                if(data == 'deleted'){
                  Swal.fire(
                    'Deleted!',
                    'Item deleted successfully.',
                    'success'
                  ).then(() => {
                    location.reload();
                  });
                }else if(data == 'no_permission'){
                  Swal.fire(
                    'Error!',
                    'You don\'t have permission to delete items.',
                    'error'
                  )
                }else if(data == 'invalid_id'){
                  Swal.fire(
                    'Error!',
                    'Invalid item ID.',
                    'error'
                  )
                }else if(data == 'item_not_found'){
                  Swal.fire(
                    'Error!',
                    'Item not found.',
                    'error'
                  )
                }else if(data.startsWith('database_error:')){
                  Swal.fire(
                    'Error!',
                    'Database error: ' + data.replace('database_error: ', ''),
                    'error'
                  )
                  console.log('Database error details:', data)
                }else{
                  Swal.fire(
                    'Error!',
                    'Failed to delete item: ' + data,
                    'error'
                  )
                  console.log('Error details:', data)
                }
              },
              error: function(xhr, status, error){
                Swal.fire(
                  'Error!',
                  'Network error: ' + error,
                  'error'
                )
                console.log('AJAX Error:', xhr.responseText)
              }
            })
          }
        })
      })


      function get_items(){
        $.ajax({
            url:'../jquery/add-item.php',
            type:'post',
            data: 'getItems=24',
            success: function(data){
                $('tbody').html(data)
            }
        });
      }

      // Displaying customer detail
      $(document).on('click', '#view_item', function(){
        var id = $(this).data('id')
        $('.overlay').removeClass('d-none');
        $.ajax({
            url:'../jquery/add-item.php',
            type:'post',
            data: 'viewSingleItem='+id,
            success: function(data){
                $('#itemDetailDesp').html(data)
                $('.overlay').addClass('d-none');
                

            }
        });
      })

      // Edit customer - displaying information
      $(document).on('click', '#edit_item', function(){
        var id = $(this).data('id')
        console.log('Edit item clicked, ID:', id); // Debug log
        
        if(!id){
          toastr.error('Item ID not found');
          return;
        }
        
        $.ajax({
            url:'../jquery/add-item.php',
            type:'post',
            data: {editSingleItem: id},
            beforeSend: function(){
              $('.editItemPlace').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
            },
            success: function(data){
                console.log('Item data loaded'); // Debug log
                $('.editItemPlace').html(data)
            },
            error: function(xhr, status, error){
                console.log('Error loading item:', error); // Debug log
                toastr.error('Failed to load item data');
                $('.editItemPlace').html('<div class="alert alert-danger">Failed to load item data. Please try again.</div>');
            }
        });
      })

      //Updating Item Detail
      $(document).on('submit', '#frmEditItem', function(e){
        e.preventDefault();
        console.log('Edit item form submitted'); // Debug log
        
        $.ajax({
          url:'../jquery/add-item.php', 
          type:'post', 
          cache: false,
          contentType: false, // you can also use multipart/form-data replace of false
          processData: false,
          data: new FormData(this),
          success: function(res){
            res = res.trim(); // Trim whitespace
            console.log('Edit item response:', res); // Debug log
            
            if(res == 'found_iname'){
              toastr.error("This item name is registered with another item.")
              $('#iname1').addClass('is-invalid')
            }else if(res == 'found_barcode'){
              toastr.error("This barcode is already in use, try another one")
              $('#barcode1').addClass('is-invalid')
            }else if (res == 'updated'){
              toastr.success("Item updated successfully")
              $('#frmEditItem')[0].reset();
              $('#editItemModal').modal('hide');
              $('#iname1').removeClass('is-invalid')
              $('#barcode1').removeClass('is-invalid')
              
              // Reload page after 1 second
              setTimeout(function(){
                location.reload();
              }, 1000);
            }else if(res.startsWith('database_error:')){
              toastr.error("Database error: " + res.replace('database_error: ', ''))
              console.log('Database error details:', res)
            }else if(res == 'no_permission'){
              toastr.error("You don't have permission to edit items")
            }else if(res == 'missing_fields'){
              toastr.error("Please fill in all required fields")
            }else if(res == 'invalid_values'){
              toastr.error("Please enter valid values (quantities and prices must be positive)")
            }else{
              toastr.error("Failed to update item: " + res)
              console.log('Error details:', res)
            }
          },
          error: function(xhr, status, error){
            toastr.error("Network error: " + error)
            console.log("AJAX Error:", xhr.responseText)
          }
        })
      });




})

</script>
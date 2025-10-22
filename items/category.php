<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Item Category"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Items'  ?>
      
      <?php $smenu = 'Category'  ?>
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
                  <h1 class="page-title"> Item Category </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="supplier/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Categories </a> -->
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                  <div class="row">
                    <div class="col-md-4">
  
                      <div class="card bg-light ">
                        <div class="overlay d-none">
                            <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                        </div>
                        
                        <div class="card-body ">

                          
                          <form class='row' method='post' id='frmCategory'>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Category  name *</label>
                              <input type='text' name='cname' id='cname' class='form-control rounded-0' autocomplete="off" required>
                            </div>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Description </label>
                             <textarea name="desc" id="desc" class="form-control rounded-0"></textarea>
                            </div>
  
                            <div class="form-group col-md-12">
                              <button type="submit" class="btn btn-info"> <i class="fa fa-plus"></i> Save category</button>
                            </div>
  
                          </form>

                          
                          
                        <!-- Edit category form -->
                          <form class='row d-none' method='post' id='frmEditCat'>
  


                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="card bg-light">
                      <div class="card-body ">
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Category name</th>
                              <th>Description</th>
                              <th>Date</th>
                              <th></th>
                             
                            </tr>
                          </thead>
                          <tbody>
                          
                             <?php 
                              $i = 0;
                              $stmt = "SELECT * FROM item_category ORDER BY itemcat_id DESC";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $i++;
                                $id = $row['itemcat_id'];
                                $name = $row['category_name'];
                                $des = $row['description'];
                                $date = date("M d, Y", strtotime($row['reg_date']));
                                if($des == "" ){ $des = "N/A"; }

                                echo "
                                  <tr>
                                    <td>$i</td>
                                    <td>$name</td>
                                    <td>$des</td>
                                    <td>$date</td>
                                    <td>
                                      <div class='dropdown d-inline-block'>
                                        <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                        <div class='dropdown-menu dropdown-menu-right' style=''>
                                          <div class='dropdown-arrow'></div>
                                          <button type='button' data-toggle='modal' data-target='#editCustModal' id='edit_cat' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Category</button>
                                          <button type='button' class='dropdown-item btn-danger' id='del_cat' data-id='$id'><i class='fa fa-trash'></i> Delete Category</button>
                                        </div>
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

                  </div>


                </div><!-- /.section-block -->
             

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

  $('#frmCategory').on('submit', function(e){
    e.preventDefault();
    
    // Client-side validation
    var categoryName = $('#cname').val().trim();
    if(categoryName === ''){
      toastr.error("Category name is required")
      $('#cname').addClass('is-invalid')
      return false;
    }
    
    $('.overlay').removeClass('d-none')

    $.ajax({
      url:'../jquery/item-category.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmCategory').serialize(),
      beforeSend: function(){
        console.log('Sending category data:', $('#frmCategory').serialize());
      },
      success: function(res){
        $('.overlay').addClass('d-none')
        console.log('Server response:', res);
        
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
          if(res.error === 'found_cat'){
            toastr.error("This category is already registered, try another one.")
            $('#cname').addClass('is-invalid')
          }else if(res.success === true){
            toastr.success("Category registered successfully")
            $('#frmCategory')[0].reset();
            $('#cname').removeClass('is-invalid')
            get_categories()
            location.reload()
          }else if(res.error === 'not_logged_in'){
            toastr.error("You must be logged in to add categories")
            window.location.href = '<?= BASE_URL ?>login.php'
          }else if(res.error === 'empty_name'){
            toastr.error("Category name cannot be empty")
            $('#cname').addClass('is-invalid')
          }else if(res.error === 'database_error'){
            toastr.error("Database error occurred. Please try again.")
            if(res.message) console.error(res.message)
          }else if(res.error === 'table_not_found'){
            toastr.error("Database table not found. Please contact administrator.")
          }else if(res.error === 'insert_error'){
            toastr.error("Failed to save category. Please try again.")
            if(res.message) console.error(res.message)
          }else{
            toastr.error("An unexpected error occurred. Please try again.")
            console.error('Unexpected response:', res)
          }
        }else{
          // Handle legacy string responses (fallback)
          if(res == 'found_cat'){
            toastr.error("This category is already registered, try another one.")
            $('#cname').addClass('is-invalid')
          }else if(res == 'success'){
            toastr.success("Category registered successfully")
            $('#frmCategory')[0].reset();
            $('#cname').removeClass('is-invalid')
            get_categories()
            location.reload()
          }else{
            toastr.error("An unexpected error occurred. Please try again.")
            console.error('Unexpected response:', res)
          }
        }
      },
      error: function(xhr, status, error){
        $('.overlay').addClass('d-none')
        toastr.error("Network error occurred. Please check your connection.")
        console.error('AJAX Error:', error)
      }
    })
  })


  function get_categories(){
   
    $.ajax({
      url:'../jquery/item-category.php', 
      type:'post', 
      data: {all_categores: true},
      success: function(res){
        $('tbody').html(res)
      }
    });
  }

      //Delete product
      $(document).on('click', '#del_cat', function(){
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
                  url:'../jquery/item-category.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_cat='+id,
                  success: function(data){
                    if(data.success === true){
                      Swal.fire(
                        'Deleted!',
                        'Category deleted successfully.',
                        'success'
                      )
                      get_categories();
                      location.reload()
                    }else if(data.error === 'not_logged_in'){
                      Swal.fire(
                        'Session Expired!',
                        'Please login again.',
                        'error'
                      ).then(() => {
                        window.location.href = '<?= BASE_URL ?>login.php';
                      });
                    }else{
                      Swal.fire(
                        'Error!',
                        'Something is wrong please try again.',
                        'error'
                      )
                      
                    }
                  },
                  error: function(xhr, status, error){
                    console.log('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                    Swal.fire(
                      'Network Error!',
                      'Please try again.',
                      'error'
                    );
                  }
                })

              }
            })
      })
    
    $(document).on('click', '#edit_cat', function(e){
      e.preventDefault();
      var id = $(this).data('id')
      console.log('Edit category clicked, ID:', id); // Debug log
      
      if(!id){
        toastr.error('Category ID not found');
        return;
      }
      
      $.ajax({
          url:'../jquery/item-category.php',
          type:'post',
          dataType: 'text', // Expect plain text/HTML response
          data: {edit_cat: id},
          beforeSend: function(){
            $('#frmEditCat').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
            $('#frmEditCat').removeClass('d-none')
            $('#frmCategory').addClass('d-none')
          },
          success: function(data){
            console.log('Edit category data loaded'); // Debug log
            $('#frmEditCat').html(data)
            $('#frmEditCat').removeClass('d-none')
            $('#frmCategory').addClass('d-none')
          },
          error: function(xhr, status, error){
            console.log('Error loading category:', error);
            toastr.error('Failed to load category data');
            $('#frmEditCat').html('<div class="alert alert-danger">Failed to load category data. Please try again.</div>');
          }
      })
    })

  $(document).on('submit', '#frmEditCat', function(e){
    e.preventDefault()
    console.log('Edit category form submitted'); // Debug log
    
    $.ajax({
          url:'../jquery/item-category.php',
          type:'post',
          dataType: 'text', // Expect plain text response
          data: $('#frmEditCat').serialize(),
          success: function(data){
            data = data.trim(); // Trim whitespace
            console.log('Edit category response:', data); // Debug log
            
            if(data == 'updated'){
              $('#frmEditCat')[0].reset();
              $('#frmEditCat').addClass('d-none')
              $('#frmCategory').removeClass('d-none')
              $('#cname1').removeClass('is-invalid')
              toastr.success("Category updated successfully")
              
              setTimeout(function(){
                location.reload();
              }, 1000);
            }else if(data == "found_cat"){
              toastr.error("This category is already registered, try another one")
              $('#cname1').addClass('is-invalid')
            }else if(data.startsWith('database_error:')){
              toastr.error("Database error: " + data.replace('database_error: ', ''))
              console.log('Database error details:', data)
            }else if(data == 'no_permission'){
              toastr.error("You don't have permission to update categories")
            }else if(data == 'missing_fields'){
              toastr.error("Please fill in all required fields")
            }else if(data == 'category_not_found'){
              toastr.error("Category not found")
            }else if(data == 'invalid_id'){
              toastr.error("Invalid category ID")
            }else{
              toastr.error("Failed to update category: " + data)
              console.log('Error details:', data)
            }
          },
          error: function(xhr, status, error){
            toastr.error("Network error: " + error)
            console.log("AJAX Error:", xhr.responseText)
          }
      })
  })
  
  // Cancel edit button
  $(document).on('click', '#btnCancelEdit', function(){
    $('#frmEditCat')[0].reset();
    $('#frmEditCat').addClass('d-none')
    $('#frmCategory').removeClass('d-none')
    $('#cname1').removeClass('is-invalid')
  })

})

</script>
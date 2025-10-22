<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Unit measurement"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Alaab'  ?>
      
      <?php $smenu = 'Unit measurement '  ?>
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
                  <h1 class="page-title"> Unit measurement </h1>
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

                          
                          <form class='row' method='post' id='frmAddUnit'>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Unit name *</label>
                              <input type='text' name='uname' id='uname' minlength='5' class='form-control rounded-0' autocomplete="off" required>
                            </div>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Short name </label>
                              <input type="text" name="sname" id="sname" maxlength='3' class="form-control">
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
                              <th>Unit name</th>
                              <th>Short name</th>
                              <th>Date</th>
                              <th></th>
                             
                            </tr>
                          </thead>
                          <tbody>
                          
                             <?php 
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
                                      <div class='dropdown d-inline-block'>
                                        <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                        <div class='dropdown-menu dropdown-menu-right' style=''>
                                          <div class='dropdown-arrow'></div>
                                          <button type='button' id='edit_unit' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Unit</button>
                                          <button type='button' class='dropdown-item btn-danger' id='del_unit' data-id='$id'><i class='fa fa-trash'></i> Delete Unit</button>
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

  $('#frmAddUnit').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')

    $.ajax({
      url:'../jquery/unit.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmAddUnit').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        
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
            toastr.error("You must be logged in to add units")
            window.location.href = '<?= BASE_URL ?>login.php'
          }else if(res.error === 'found_unit'){
            toastr.error("This unit is already registered, try another one.")
            $('#uname').addClass('is-invalid')
          }else if(res.error === 'found_sname'){
            toastr.error("The short name you entered is already registered, try another one.")
            $('#sname').addClass('is-invalid')
          }else if(res.success === true){
            toastr.success("Unit registered successfully")
            $('#frmAddUnit')[0].reset();
            $('#uname').removeClass('is-invalid')
            $('#sname').removeClass('is-invalid')
            get_units()
            location.reload()
          }else{
            toastr.error("An unexpected error occurred. Please try again.")
            console.error('Unexpected response:', res)
          }
        }else{
          // Handle legacy string responses (fallback)
          if(res == 'found_unit'){
            toastr.error("This unit is already registered, try another one.")
            $('#uname').addClass('is-invalid')
          }else if (res == "found_sname"){
            toastr.error("The short name you entered is already registered, try another one.")
            $('#sname').addClass('is-invalid')
          }else if (res == 'success'){
            toastr.success("Unit registered successfully")
            location.reload()
            $('#frmAddUnit')[0].reset();
            $('#uname').removeClass('is-invalid')
            get_units()
          }else{
            toastr.error("Something is wrong please try again")
            console.log(res)
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


  function get_units(){
   
    $.ajax({
      url:'../jquery/unit.php', 
      type:'post', 
      data: {all_unit: true},
      success: function(res){
        $('tbody').html(res)
      }
    });
  }

      //Delete product
      $(document).on('click', '#del_unit', function(){
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
                  url:'../jquery/unit.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_unit='+id,
                  success: function(data){
                    if(typeof data === 'object'){
                      if(data.error === 'not_logged_in'){
                        Swal.fire(
                          'Session Expired!',
                          'You must be logged in to delete units.',
                          'error'
                        ).then(() => {
                          window.location.href = '<?= BASE_URL ?>login.php'
                        })
                      }else if(data.success === true){
                        Swal.fire(
                          'Deleted!',
                          'Unit deleted successfully.',
                          'success'
                        )
                        get_units();
                        location.reload()
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
                          'Unit deleted successfully.',
                          'success'
                        )
                        get_units();
                        location.reload()
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
    
    $(document).on('click', '#edit_unit', function(e){
      e.preventDefault();
      var id = $(this).data('id')
      console.log('Edit unit clicked, ID:', id); // Debug log
      
      if(!id){
        toastr.error('Unit ID not found');
        return;
      }
      
      $.ajax({
          url:'../jquery/unit.php',
          type:'post',
          dataType: 'text', // Expect plain text/HTML response
          data: {edit_unit: id},
          beforeSend: function(){
            $('#frmEditCat').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
            $('#frmEditCat').removeClass('d-none')
            $('#frmAddUnit').addClass('d-none')
          },
          success: function(data){
            console.log('Edit unit data loaded'); // Debug log
            $('#frmEditCat').html(data)
            $('#frmEditCat').removeClass('d-none')
            $('#frmAddUnit').addClass('d-none')
          },
          error: function(xhr, status, error){
            console.log('Error loading unit:', error);
            toastr.error('Failed to load unit data');
            $('#frmEditCat').html('<div class="alert alert-danger">Failed to load unit data. Please try again.</div>');
          }
      })
    })

  $(document).on('submit', '#frmEditCat', function(e){
    e.preventDefault()
    console.log('Edit unit form submitted'); // Debug log
    
    $.ajax({
          url:'../jquery/unit.php',
          type:'post',
          dataType: 'json', // Expect JSON response
          data: $('#frmEditCat').serialize(),
          success: function(data){
            console.log('Edit unit response:', data); // Debug log
            
            // Handle JSON response
            if(typeof data === 'object'){
              if(data.error === 'not_logged_in'){
                toastr.error("You must be logged in to update units")
                setTimeout(function(){
                  window.location.href = '<?= BASE_URL ?>login.php'
                }, 2000);
              }else if(data.success === true){
                $('#frmEditCat')[0].reset();
                $('#frmEditCat').addClass('d-none')
                $('#frmAddUnit').removeClass('d-none')
                $('#uname1').removeClass('is-invalid')
                $('#sname1').removeClass('is-invalid')
                toastr.success("Unit updated successfully")
                
                setTimeout(function(){
                  location.reload();
                }, 1000);
              }else if(data.error === "found_uname"){
                toastr.error("The unit name you entered is already registered, try another one")
                $('#uname1').addClass('is-invalid')
              }else if(data.error === "found_sname"){
                toastr.error("The short name you entered is already registered, try another one")
                $('#sname1').addClass('is-invalid')
              }else if(data.error === 'update_error'){
                toastr.error("Failed to update unit. Please try again.")
                if(data.message) console.error(data.message)
              }else if(data.error === 'missing_fields'){
                toastr.error("Please fill in all required fields")
              }else if(data.error === 'unit_not_found'){
                toastr.error("Unit not found")
              }else if(data.error === 'invalid_id'){
                toastr.error("Invalid unit ID")
              }else{
                toastr.error("An unexpected error occurred. Please try again.")
                console.error('Unexpected response:', data)
              }
            }else{
              // Handle legacy string responses (fallback)
              data = String(data).trim();
              if(data == 'success'){
                toastr.success("Unit updated successfully")
                $('#frmEditCat')[0].reset();
                $('#frmEditCat').addClass('d-none')
                $('#frmAddUnit').removeClass('d-none')
                setTimeout(function(){
                  location.reload();
                }, 1000);
              }else{
                toastr.error("Failed to update unit: " + data)
                console.log('Error details:', data)
              }
            }
          },
          error: function(xhr, status, error){
            toastr.error("Network error: " + error)
            console.log("AJAX Error:", xhr.responseText)
          }
      })
  })
  
  // Cancel edit button
  $(document).on('click', '#btnCancelEditUnit', function(){
    $('#frmEditCat')[0].reset();
    $('#frmEditCat').addClass('d-none')
    $('#frmAddUnit').removeClass('d-none')
    $('#uname1').removeClass('is-invalid')
    $('#sname1').removeClass('is-invalid')
  })

})

</script>
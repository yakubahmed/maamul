<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Expense type"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Kharashka'  ?>
      
      <?php $smenu = 'Nooca kharashka'  ?>
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
                  <h1 class="page-title"> Expense type </h1>
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

                          
                          <form class='row' method='post' id='frmAddRole'>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Expense type name *</label>
                              <input type='text' name='etname' id='etname' minlength='3' maxlength='30' class='form-control rounded-0' autocomplete="off" required>
                            </div>
  
                            <div class='form-group col-md-12'>
                              <label for=''> Description </label>
                              <textarea name="desc" id="desc"  class='form-control'></textarea>
                            </div>
  
                            <div class="form-group col-md-12">
                              <button type="submit" class="btn btn-info btn-block"> <i class="fa fa-plus"></i> Save expense type</button>
                            </div>
  
                          </form>

                          
                          
                        <!-- Edit category form -->
                          <form class='row d-none' method='post' id='frmEditRole'>
  


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
                              <th>Expense type name</th>
                              <th>Description</th>
                              <th>Date</th>
                              <th></th>
                             
                            </tr>
                          </thead>
                          <tbody>
                          
                             <?php 
                              $i = 0;
                              $stmt = "SELECT * FROM expense_type ORDER BY expense_type_id DESC";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $i++;
                                $id = $row['expense_type_id'];
                                $name = $row['name'];
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
                                          <button type='button' id='edit_etype' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Expense Type</button>
                                          <button type='button' class='dropdown-item btn-danger' id='del_etype' data-id='$id'><i class='fa fa-trash'></i> Delete Expense Type</button>
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

  $('#frmAddRole').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')

    $.ajax({
      url:'../jquery/expense-type.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmAddRole').serialize(),
      success: function(res){
        if(res.error === 'found_etype'){
          toastr.error("This expense name you entered is allready registered, try another one.")
          $('.overlay').addClass('d-none')
          $('#etname').addClass('is-invalid')
        }else if (res.error === 'empty_name'){
          toastr.error("Please enter a valid expense type name")
          $('.overlay').addClass('d-none')
          $('#etname').addClass('is-invalid')
        }else if (res.error === 'not_logged_in'){
          toastr.error("Session expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        }else if (res.error === 'Session expired'){
          toastr.error("Session expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        }else if (res.success === true){
          toastr.success("Expense type registered successfully")
          $('#frmAddRole')[0].reset();
          $('.overlay').addClass('d-none')
          $('#etname').removeClass('is-invalid')
          location.reload()
        }else{
            toastr.error("Something is wrong please try again")
            console.log(res)
        }
      },
      error: function(xhr, status, error){
        console.log('AJAX Error:', error);
        console.log('Response:', xhr.responseText);
        toastr.error("Network error. Please try again.");
        $('.overlay').addClass('d-none');
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
      $(document).on('click', '#del_etype', function(){
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
                  url:'../jquery/expense-type.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_etype='+id,
                  success: function(data){
                    if(data.success === true){
                      Swal.fire(
                        'Deleted!',
                        'Expense type deleted successfully.',
                        'success'
                      )
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
                  }
                })

              }
            })
      })
    
    $(document).on('click', '#edit_etype', function(e){
      e.preventDefault();
       var id = $(this).data('id')
      $.ajax({
          url:'../jquery/expense-type.php',
          type:'post',
          data: 'edit_etype='+id,
          success: function(data){
            
            $('#frmEditRole').html(data)
            $('#frmEditRole').removeClass('d-none')
            $('#frmAddRole').addClass('d-none')
          },
          error: function(xhr, status, error){
            console.log('AJAX Error:', error);
            console.log('Response:', xhr.responseText);
            toastr.error('Failed to load expense type details.');
          }
                  
      })
    })

  $('#frmEditRole').on('submit', function(e){
    e.preventDefault()
    $.ajax({
          url:'../jquery/expense-type.php',
          type:'post',
          dataType: 'json',
          data: $('#frmEditRole').serialize(),
          success: function(data){
            if(data.success === true){
              $('#frmEditRole')[0].reset();
              $('#frmEditRole').addClass('d-none')
              $('#frmAddRole').removeClass('d-none')
              $('#rname1').removeClass('is-invalid')
              toastr.success("Updated Successfully")
                location.reload()
            }else if(data.error === "found_etype"){
              toastr.error("The expense type name that you entered is allready registered please another again")
              $('#rname1').addClass('is-invalid')
            }else if(data.error === 'not_logged_in'){
              toastr.error("Session expired. Please login again.")
              setTimeout(function(){
                window.location.href = '<?= BASE_URL ?>login.php';
              }, 2000);
            }else{
              toastr.error("Something is wrong please try again")
              console.log(data)
            }

          },
          error: function(xhr, status, error){
            console.log('AJAX Error:', error);
            console.log('Response:', xhr.responseText);
            toastr.error("Network error. Please try again.");
          }
                  
      })
  })

})

</script>

<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Expense list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Expense'  ?>
      
      <?php $smenu = 'Expense list'  ?>
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
                  <h1 class="page-title"> Expense list </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>expense/new" class="btn btn-info text-right"> <i class="fa fa-plus"></i> Add expense </a>
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
                              <th>Expense type</th>
                              <th>For</th>
                              <th>Description</th>
                              <th>Date</th>
                              <th>Amount</th>
                              <th>Account</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                $i = 0;
                                $stmt = "SELECT expense.*, expense_type.name as etype, account.account_name, account.account_number 
                                FROM expense, expense_type, account WHERE expense.expense_type = expense_type.expense_type_id 
                                AND expense.account = account.account_id ";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['expense_id'];
                                    $name = $row['expense_name'];
                                    $type = $row['etype'];
                                    $desc = $row['description'];
                                    $amount = $row['amount'];
                                    $acc = $row['account_name'] . ' - ' . $row['account_number'];
                                    $date = date("M d, Y", strtotime($row['reg_date']));
                                   
                

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$type</td>
                                            <td>$name</td>
                                            <td>$desc </td>
                                            <td>$date</td>
                                            <td> <strong>$ $amount</strong> </td>
                                            <td> $acc </td>
                                            <td>
                                                <div class='dropdown d-inline-block'>
                                                  <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                  <div class='dropdown-menu dropdown-menu-right' style=''>
                                                    <div class='dropdown-arrow'></div>
                                                    <button type='button' data-toggle='modal' data-target='#editAccModal' id='edit_exp' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Expense</button>
                                                    <button type='button' class='dropdown-item btn-danger' id='del_exp' data-id='$id'><i class='fa fa-trash'></i> Delete Expense</button>
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

                </div><!-- /.section-block -->
             


              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="editAccModal" tabindex="-1" role="dialog" aria-labelledby="editAccModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Edit Expense</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='editExpPlace row' method='post' id='frmEditAcc' >
                                
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
      $(document).on('click', '#del_exp', function(){
        var id = $(this).data('id');
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
                  url:'../jquery/expense.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_exp='+id,
                  success: function(data){
                    if(data.success === true){
                      Swal.fire(
                        'Deleted!',
                        'Expense deleted successfully.',
                        'success'
                      )
                      setTimeout(() => {
                        location.reload();
                      }, 500);
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
      $(document).on('click', '#edit_exp', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/expense.php',
            type:'post',
            data: 'editSingleExp='+id,
            success: function(data){
                $('.editExpPlace').html(data)
                //$('.overlay').addClass('d-none');

            },
            error: function(xhr, status, error){
              console.log('AJAX Error:', error);
              console.log('Response:', xhr.responseText);
              toastr.error("Failed to load expense details.");
            }
        });
      })

      //Updating Customer Detail
      $('#frmEditAcc').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/expense.php', 
          type:'post', 
          dataType: 'json',
          data: $('#frmEditAcc').serialize(),
          success: function(res){
            if (res.success === true){
              toastr.success("Expense updated successfully")
              $('#frmEditAcc')[0].reset();
              $('#editAccModal').modal('hide');

              location.reload();
            }else if (res.error === 'not_logged_in'){
              toastr.error("Session expired. Please login again.")
              setTimeout(function(){
                window.location.href = '<?= BASE_URL ?>login.php';
              }, 2000);
            }else if (res.error === 'invalid_input'){
              toastr.error("Please fill in all required fields with valid data")
            }else{
              toastr.error("Something is wrong please try again.")
              console.log(res)
            }
          },
          error: function(xhr, status, error){
            console.log('AJAX Error:', error);
            console.log('Response:', xhr.responseText);
            toastr.error("Network error. Please try again.");
          }
        })
      });




})

</script>

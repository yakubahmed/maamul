<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Account list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Xisaabaadka'  ?>
      
      <?php $smenu = 'Liiska akoonada'  ?>
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
                  <h1 class="page-title"> Account list </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>account/add" class="btn btn-info text-right"> <i class="fa fa-user-plus"></i> Add account </a>
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
                        <!-- Filter Toggle Button -->
                        <div class="row mb-3">
                          <div class="col-md-12">
                            <button type="button" id="btn_toggle_filters" class="btn btn-info">
                              <i class="fa fa-filter"></i> Show Advanced Filters
                            </button>
                            <?php if(!empty($filter_date_from) || !empty($filter_date_to)): ?>
                              <span class="badge badge-success ml-2">
                                <i class="fa fa-check"></i> Filters Active
                                <?php if(!empty($filter_date_from)): ?>
                                  From: <?= date('M d, Y', strtotime($filter_date_from)) ?>
                                <?php endif; ?>
                                <?php if(!empty($filter_date_to)): ?>
                                  To: <?= date('M d, Y', strtotime($filter_date_to)) ?>
                                <?php endif; ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                        
                        <!-- Advanced Filters (Hidden by default) -->
                        <div id="advanced_filters" style="display: none;">
                          <div class="row mb-3">
                            <div class="col-md-4">
                              <label for="filter_date_from">Date From</label>
                              <input type="date" id="filter_date_from" class="form-control">
                            </div>
                            <div class="col-md-4">
                              <label for="filter_date_to">Date To</label>
                              <input type="date" id="filter_date_to" class="form-control">
                            </div>
                            <div class="col-md-2">
                              <label>&nbsp;</label>
                              <button type="button" id="btn_filter" class="btn btn-primary btn-block">
                                <i class="fa fa-check"></i> Apply
                              </button>
                            </div>
                            <div class="col-md-2">
                              <label>&nbsp;</label>
                              <button type="button" id="btn_reset" class="btn btn-secondary btn-block">
                                <i class="fa fa-redo"></i> Reset
                              </button>
                            </div>
                          </div>
                          <hr>
                        </div>
                        
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Account name</th>
                              <th>Number</th>
                              <th>Description</th>
                              <th>Balance</th>
                              <th>Date</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              function get_balance($id, $date_from = null, $date_to = null){
                                global $con; 
                                
                                // Build date filter conditions for payment
                                $payment_date_filter = "";
                                if(!empty($date_from) && !empty($date_to)){
                                  $payment_date_filter = " AND DATE(payment.date) BETWEEN '$date_from' AND '$date_to'";
                                } elseif(!empty($date_from)){
                                  $payment_date_filter = " AND DATE(payment.date) >= '$date_from'";
                                } elseif(!empty($date_to)){
                                  $payment_date_filter = " AND DATE(payment.date) <= '$date_to'";
                                }
                                
                                // Get total sales income (from customer payments)
                                $stmt = "SELECT SUM(amount) FROM payment WHERE account = $id $payment_date_filter";
                                $result = mysqli_query($con, $stmt);
                                $row = mysqli_fetch_array($result);
                                $sales_income = $row[0];
                                if(empty($sales_income)){$sales_income = 0;}
                                
                                // Get total expenses for this account with date filter
                                $expense_date_filter = "";
                                if(!empty($date_from) && !empty($date_to)){
                                  $expense_date_filter = " AND DATE(reg_date) BETWEEN '$date_from' AND '$date_to'";
                                } elseif(!empty($date_from)){
                                  $expense_date_filter = " AND DATE(reg_date) >= '$date_from'";
                                } elseif(!empty($date_to)){
                                  $expense_date_filter = " AND DATE(reg_date) <= '$date_to'";
                                }
                                $s = "SELECT SUM(amount) FROM expense WHERE account = $id $expense_date_filter";
                                $re = mysqli_query($con, $s);
                                $rw = mysqli_fetch_array($re);
                                $expense = $rw[0];
                                if(empty($expense)){$expense = 0;}
                                
                                // Get total purchase expenses (supplier payments)
                                $purchase_date_filter = "";
                                if(!empty($date_from) && !empty($date_to)){
                                  $purchase_date_filter = " AND DATE(date) BETWEEN '$date_from' AND '$date_to'";
                                } elseif(!empty($date_from)){
                                  $purchase_date_filter = " AND DATE(date) >= '$date_from'";
                                } elseif(!empty($date_to)){
                                  $purchase_date_filter = " AND DATE(date) <= '$date_to'";
                                }
                                $p = "SELECT SUM(amount) FROM pur_payments WHERE account = $id $purchase_date_filter";
                                $pr = mysqli_query($con, $p);
                                $prw = mysqli_fetch_array($pr);
                                $purchase = $prw[0];
                                if(empty($purchase)){$purchase = 0;}
                                
                                // Balance = Sales Income - Expenses - Purchase Expenses
                                $total = $sales_income - $expense - $purchase;

                                return number_format($total, 2, '.', ',');
                              }
                              
                              // Get date filters from request if available
                              $filter_date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
                              $filter_date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;
                            ?>
                              <?php 
                                $i = 0;
                                $stmt = "SELECT * FROM account ORDER by account_id DESC";
                                $result = mysqli_query($con, $stmt); 
                                while($row = mysqli_fetch_assoc($result)){
                                    $i++;
                                    $id = $row['account_id'];
                                    $name = $row['account_name'];
                                    $num = $row['account_number'];
                                    $desc = $row['description'];
                                    $date = date("M d, Y", strtotime($row['reg_date']));
                                   
                                    $balance = get_balance($id, $filter_date_from, $filter_date_to);

                                    echo "
                                        <tr>
                                            <td>$i</td>
                                            <td>$name</td>
                                            <td>$num</td>
                                            <td>$desc </td>
                                            <td> <strong>$ $balance</strong> </td>
                                            <td>$date</td>
                                            <td>
                                                <div class='dropdown d-inline-block'>
                                                  <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                  <div class='dropdown-menu dropdown-menu-right' style=''>
                                                    <div class='dropdown-arrow'></div>
                                                    <button type='button' data-toggle='modal' data-target='#editAccModal' id='edit_acc' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Account</button>
                                                    <button type='button' class='dropdown-item btn-danger' id='del_acc' data-id='$id'><i class='fa fa-trash'></i> Delete Account</button>
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
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Edit Account</strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='editAccPlace row' method='post' id='frmEditAcc' >
                                
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

  // Toggle Advanced Filters
  $('#btn_toggle_filters').on('click', function(){
    $('#advanced_filters').slideToggle();
    var btnText = $(this).find('i').hasClass('fa-filter') ? 'Hide Advanced Filters' : 'Show Advanced Filters';
    var btnIcon = $(this).find('i').hasClass('fa-filter') ? 'fa-times' : 'fa-filter';
    $(this).html('<i class="fa ' + btnIcon + '"></i> ' + btnText);
  });

  // Advanced Filtering - Reload page with date parameters
  var table = $('#example1').DataTable();
  
  // Set initial date values from URL parameters
  var urlParams = new URLSearchParams(window.location.search);
  var hasFilters = false;
  
  if(urlParams.has('date_from')){
    $('#filter_date_from').val(urlParams.get('date_from'));
    hasFilters = true;
  }
  if(urlParams.has('date_to')){
    $('#filter_date_to').val(urlParams.get('date_to'));
    hasFilters = true;
  }
  
  // Auto-show filters if active
  if(hasFilters){
    $('#advanced_filters').show();
    $('#btn_toggle_filters').html('<i class="fa fa-times"></i> Hide Advanced Filters');
  }
  
  $('#btn_filter').on('click', function(){
    var dateFrom = $('#filter_date_from').val();
    var dateTo = $('#filter_date_to').val();
    
    // Build URL with date parameters
    var url = window.location.pathname;
    var params = [];
    
    if(dateFrom){
      params.push('date_from=' + dateFrom);
    }
    if(dateTo){
      params.push('date_to=' + dateTo);
    }
    
    if(params.length > 0){
      url += '?' + params.join('&');
    }
    
    // Reload page with filters
    window.location.href = url;
  });
  
  $('#btn_reset').on('click', function(){
    // Reload page without parameters
    window.location.href = window.location.pathname;
  });



      //Delete product
      $(document).on('click', '#del_acc', function(){
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
                  url:'../jquery/account.php',
                  type:'post',
                  dataType: 'json',
                  data: 'del_acc='+id,
                  success: function(data){
                    if(data.success === true){
                      Swal.fire(
                        'Deleted!',
                        'Account deleted successfully.',
                        'success'
                      )
                      location.reload();
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
      $(document).on('click', '#edit_acc', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/account.php',
            type:'post',
            data: 'editSingleAcc='+id,
            success: function(data){
                $('.editAccPlace').html(data)
                //$('.overlay').addClass('d-none');

            },
            error: function(xhr, status, error){
              console.log('AJAX Error:', error);
              console.log('Response:', xhr.responseText);
              toastr.error('Failed to load account details.');
            }
        });
      })

      //Updating Customer Detail
      $('#frmEditAcc').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
          url:'../jquery/account.php', 
          type:'post', 
          dataType: 'json',
          data: $('#frmEditAcc').serialize(),
          success: function(res){
            if(res.error === 'found_accnum'){
              toastr.error("This account number is registered with other account.")
              $('#accnum1').addClass('is-invalid')
            }else if (res.error === 'empty_fields'){
              toastr.error("Please fill in all required fields")
              $('#accnum1').addClass('is-invalid')
            }else if (res.error === 'not_logged_in'){
              toastr.error("Session expired. Please login again.")
              setTimeout(function(){
                window.location.href = '<?= BASE_URL ?>login.php';
              }, 2000);
            }else if (res.success === true){
              toastr.success("Account updated successfully")
              $('#frmEditAcc')[0].reset();
              $('#accnum1').removeClass('is-invalid')
              $('#editAccModal').modal('hide');

              location.reload();
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

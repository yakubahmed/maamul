<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Users report"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'User report'  ?>
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
                  <h1 class="page-title"> Users <small>report</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="<?= BASE_URL ?>customer/list" class="btn btn-info text-right"> <i class="fa fa-users"></i> View Customers </a> -->
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

                      <div class="card-body">
                      <form class='row' method='post' id='frmShowRep'>
                            <div class="form-group col-md-4">
                                <label for="">From date</label>
                                <input type="date" name="fdate" id="fdate" value="<?=date('Y-m-01') ?>" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="">To date</label>
                                <input type="date" name="tdate" id="tdate" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>


                            <div class="form-group col-md-4">
                                <label for="">User</label>
                                <select id='bss4'  name = 'user' data-toggle='selectpicker' data-live-search='true' data-width='100%' >
                                <option data-tokens='' value=''> ALL  </option>
                                <?php 
                                    $stmt = "SELECT * FROM users ORDER BY fullname ASC";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['userid '];
                                        $name = $row['fullname'];
                                        $email = $row['email_addr'];
                                        echo "
                                            <option data-tokens='$email' value='$id'>$name  </option>

                                        ";
                                    }

                                ?>
                             
                            </select>
                            
                          </div>
                          
                          <div class="form-group col-md-12 my-3 text-center">
                              <center>
                              <button type="submit" class='btn btn-info btn-block col-md-4'>Show</button>

                              </center>
                          </div>
                        
                        </form>

                        <div class="table-responsive">
                          <table class="table d-none">
                            <thead>
                              <tr class='bg-primary text-light'>
                                <th>#</th>
                                <th>Name</th>
                                <th>Total Sales</th>
                                <th>Total Purchases</th>
                                <th>Expenses</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>1</td>
                                <td>Yakub Ahmed (0616246740)</td>
                                <td> <a href="#" data-toggle='modal' data-target='#salesModal'>0</a></td>
                                <td><a href="#" data-toggle='modal' data-target='#purchaseModal'>2</a></td>
                                <td><a href="#" data-toggle='modal' data-target='#expenseModal'>5</a></td>
                              </tr>
                            
                            </tbody>
                            <tfoot>
                              <tr>
                                <th colspan='2' class='text-right'>Total: </th>
                                <th>5</th>
                                <th>9</th>
                                <th>9</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      
                      
                     
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->
             

              </div><!-- /.page-section -->
            </div><!-- /.page-inner -->
          </div><!-- /.page -->
        </div><!-- .app-footer -->

        <!-- Sales detail modal drawer -->
        <div class="modal modal-drawer fade" id="salesModal" tabindex="-1" role="dialog" aria-labelledby="salesModal" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
          <!-- .modal-content -->
          <div class="modal-content">
            <div class="overlay d-none">
                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
            </div>
            <!-- .modal-header -->
            <div class="modal-header">
              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Total purchases <small>Yakub Ahmed</small></strong> </h5>
            </div><!-- /.modal-header -->
            <!-- .modal-body -->
            <div class="modal-body" id='custDetailDisp'>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Purchase status</th>
                        <th>Paid amount</th>
                        <th>Total amount</th>
                        <th>Payment status</th>
                      </tr>
                    </thead>
                  </table>
                </div>

            </div><!-- /.modal-body -->
            <!-- .modal-footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div><!-- /.modal-footer -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

        <!-- Sales detail modal drawer -->
        <div class="modal modal-drawer fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModal" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
          <!-- .modal-content -->
          <div class="modal-content">
            <div class="overlay d-none">
                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
            </div>
            <!-- .modal-header -->
            <div class="modal-header">
              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Total Expenses <small>Yakub Ahmed</small></strong> </h5>
            </div><!-- /.modal-header -->
            <!-- .modal-body -->
            <div class="modal-body" id='custDetailDisp'>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Expense type</th>
                        <th>For</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Account</th>
                      </tr>
                    </thead>
                  </table>
                </div>

            </div><!-- /.modal-body -->
            <!-- .modal-footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div><!-- /.modal-footer -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->


        <!-- Expense detail modal drawer -->
        <div class="modal modal-drawer fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModal" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
          <!-- .modal-content -->
          <div class="modal-content">
            <div class="overlay d-none">
                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
            </div>
            <!-- .modal-header -->
            <div class="modal-header">
              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Total sales <small>Yakub Ahmed</small></strong> </h5>
            </div><!-- /.modal-header -->
            <!-- .modal-body -->
            <div class="modal-body" id='custDetailDisp'>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Sale status</th>
                        <th>Paid amount</th>
                        <th>Total amount</th>
                        <th>Payment status</th>
                      </tr>
                    </thead>
                  </table>
                </div>

            </div><!-- /.modal-body -->
            <!-- .modal-footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div><!-- /.modal-footer -->
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
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

  $('#frmShowRep').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/user-report.php', 
      type:'post', 
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        $('table').removeClass('d-none')
        $('tbody').html(res)
        $('#frmShowRep')[0].reset();

        console.log(res)
      }
    })
  })



})

</script>
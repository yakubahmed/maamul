<?php include('../path.php'); ?>

<Style>
.invoice-print {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

.invocie-header, .invoice-footer {
    text-align: center;
    padding: 10px;
}

.invoice-content {
    padding: 20px;
}

@media print {
    .print {
        width: 210mm;
        height: 297mm;
        padding: 20;
        margin: 20;
        background:#efefef;
    }

    .invoice-header, .invocie-footer {
        display: block;
        position: fixed;
        width: 100%;
    }

    .invoice-header {
        top: 0;
    }

    .invoice-footer {
        bottom: 0;
    }
 
}
</Style>

<?php $title = "Payment report"; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Warbixin'  ?>
<?php $smenu = 'Payment report'  ?>
<body>
    <!-- .app -->
    <div class="app">
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
                  <h1 class="page-title"> Payment <small>report</small> </h1>
                </div>
                <div class="col-md-6 text-right">
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
                        
                        <form class='row' method='post' id='frmShowRep'>
                            <div class="form-group col-md-3">
                                <label for="">From date</label>
                                <input type="date" name="fdate" id="fdate" value="<?=date('Y-m-01') ?>" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">To date</label>
                                <input type="date" name="tdate" id="tdate" value="<?=date('Y-m-d') ?>" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Customer</label>
                                <select name="customer" id="customer" class="form-control selectpicker" data-live-search="true">
                                    <option value="">All Customers</option>
                                    <?php 
                                        $stmt = "SELECT * FROM customer ORDER BY cust_name ASC";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo "<option value='{$row['customer_id']}'>{$row['cust_name']} - {$row['cust_phone']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Payment Account</label>
                                <select name="account" id="account" class="form-control selectpicker" data-live-search="true">
                                    <option value="">All Accounts</option>
                                    <?php 
                                        $stmt = "SELECT * FROM account ORDER BY account_name ASC";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo "<option value='{$row['account_id']}'>{$row['account_name']} - {$row['account_number']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12 text-center">
                                <button class="btn btn-primary" id='btnShowRep'><i class="fa fa-search"></i> Show Report </button>
                                <button class="btn btn-success" type="button" onclick="window.print()"><i class="fa fa-print"></i> Print </button>
                            </div>
                        </form>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div id="report_place">
                                <p class="text-center text-muted">Select date range and click "Show Report" to view payment records</p>
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
    // Initialize selectpicker
    if($('.selectpicker').length){
        $('.selectpicker').selectpicker();
    }

    // Show report
    $('#btnShowRep').on('click', function(e){
        e.preventDefault();
        
        var fdate = $('#fdate').val();
        var tdate = $('#tdate').val();
        var customer = $('#customer').val();
        var account = $('#account').val();

        if(!fdate || !tdate){
            toastr.error("Please select both from and to dates");
            return;
        }

        $('.overlay').removeClass('d-none');

        $.ajax({
            url: '../jquery/reports/payment-report.php',
            type: 'post',
            data: {
                show_report: true,
                fdate: fdate,
                tdate: tdate,
                customer: customer,
                account: account
            },
            success: function(data){
                $('#report_place').html(data);
                $('.overlay').addClass('d-none');
            },
            error: function(){
                toastr.error("Failed to load report");
                $('.overlay').addClass('d-none');
            }
        });
    });
});
</script>

</body>
</html>


<?php include('../path.php'); ?>

<style>
           .invoice-print {
    margin: 20;
    padding: 20;
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
</style>

<?php $title = "Profit and lose report"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'Profit and lose report'  ?>
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
                  <h1 class="page-title"> Profit and Lose <small>report</small> </h1>
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
                      
                      <div class="card-body ">
                        
                        <form class='row' method='post' id='frmShowRep'>

                            <div class="form-group col-md-5">
                                <label for="">From date</label>
                                <input type="date" name="fdate" value="<?=date('Y-m-01') ?>" id="fdate" class="form-control">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="">To date</label>
                                <input type="date" name="sdate" id="sdate" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>

                            <div class="form-group col-md-2 ">
                                <label for="">--</label>
                                <input type="submit" name="" id="" value='Submit' class="btn btn-info form-control">
                            </div>
                        </form>

                            <div class=" d-none" id='res'>
                              <div >
                                <div class="metric-row">
                                    <!-- metric column -->
                                    <div class="col-md-6">
                                    <!-- .metric -->
                                    <div class="metric metric-bordered">
                                        <h2 class="metric-label"> Gross profit </h2>
                                        <p class="metric-value h1">
                                        <sup>$</sup> <span class="value">
                                            1316.50                          </span>
                                        </p>
                                    </div><!-- /.metric -->
                                    </div><!-- /metric column -->
                                    <!-- metric column -->
                                    <div class="col-md-6">
                                    <!-- .metric -->
                                    <div class="metric metric-bordered">
                                        <h2 class="metric-label"> Net profit </h2>
                                        <p class="metric-value h1">
                                        <sup>$</sup> <span class="value">
                                        80.00                          </span>
                                        </p>
                                    </div><!-- /.metric -->
                                    </div><!-- /metric column -->
                        
                                        
                                </div>
                             </div>
                      


                            </div>


                            <div class="col-lg-12 d-none">


                            <div class="invoice-print" id='contentToPrint'>
                              <div class="invoice-header">
                                <center> <img src="<?= BASE_URL ?>assets/images/logo/ramaas_logo.jpg" height='200' alt=""></center>
                                  <hr>
                              </div>

                              <div class="invoice-content">
                              <div class="table-responsive">
                                <table class="table table-striped d-none">
                                    <thead>
                                        <tr class='bg-success text-light'>
                                            <th style="min-width:70px">Invoice number</th>
                                            <th style='min-width:150px;'>Date</th>
                                            <th style='min-width:250px;'>Supplier name</th>
                                            <th>Item name</th>
                                            <th>Item sales count</th>
                                            <th>Sales amount</th>
                                            
                                        </tr>
                                        
                                    </thead>
                                    <tbody id=''>
                                       
                                    </tbody>
                                  
                                </table>
                            </div>

                              </div>

                              <div class="invoice-footer">
                                 <!-- Your footer content here -->
                                 <div class="alert alert-success text-center"> Copyright reserved <?= date('Y') ?> - Ramaas Electronics & Cosmetics Centre </div>
                               </div>

                            </div>
                            </div>
                     
                        <div class="card-body">

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

  $('#frmShowRep').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')

    $.ajax({
      url:'../jquery/profit-and-lose.php', 
      type:'post', 
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
       // $('table').removeClass('d-none')
        $('#res').removeClass('d-none')
        $('#res').html(res)
        $('#frmShowRep')[0].reset();

        console.log(res)
      }
    })
  })



})


function PrintElem(elem) {
      Popup(jQuery(elem).html());
}

function Popup(data) {
  var mywindow = window.open('', 'invoice-print', 'height=1126,width=842');
  mywindow.document.write('<html><head><title>Sales report</title>');
  mywindow.document.write('<link rel="stylesheet" href="../assets/stylesheets/theme.min.css" data-skin="default">');  
  mywindow.document.write('<link rel="stylesheet" href="../assets/vendor/bootstrap-select/css/bootstrap-select.min.css">');  

  mywindow.document.write('</head><body>');
  mywindow.document.write(data);
  mywindow.document.write('</body></html>');
  mywindow.document.focus();
  mywindow.document.close();
  mywindow.print();                        
}
</script>
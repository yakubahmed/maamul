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

<?php $title = "Product Sales summary"; include(ROOT_PATH . '/inc/header.php'); ?>

      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'Product sales summary'  ?>
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
                  <h1 class="page-title"> Product sales <small>summary</small> </h1>
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
                            <div class="form-group col-md-6">
                                <label for="">From date</label>
                                <input type="date" name="fdate" id="fdate" value="<?=date('Y-m-01') ?>" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">To date</label>
                                <input type="date" name="tdate" id="tdate" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>



                            <div class="form-group col-md-12 my-3 text-center">
                                <center>
                                <button type="submit" class='btn btn-info btn-block col-md-4'>Show</button>

                                </center>
                            </div>
                            
                        </div>
                        
                        </form>

                        <div class="card-body d-none">
                        <button id='printButton' class='btn btn-primary' onclick="PrintElem('.invoice-print')">  Print</button>
                        <div class="invoice-print" id='contentToPrint'>
                            <div class="invoice-header">
                               <center> <img src="<?= BASE_URL ?>assets/images/logo/ramaas_logo.jpg" height='200' alt=""></center>
                                <hr>
                            </div>

                            <div class="invoice-content">
                                   <h1 class=' alert alert-dark text-center'>Item sales report <small id='sr_fr_to'></small> </h1>
                                   <hr>
                                   <div class="table-responsive">
                                       <table class="table table-bordered d-none">
                                           <thead>
                                             
                                               <tr class=''>
                                                   <th>#</th>
                                                   <th style="min-width:70px">Item name</th>
                                                   
                                                   <th>Unit sold</th>
                                                   
                                               </tr>
                                               
                                           </thead>
                                           <tbody id='res'>
                                              
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
    var id = $('#cust_id').val();

    var fdate = $('#fdate').val();
    var tdate = $('#tdate').val();
    //From to date display
    $('#sr_fr_to').html(fdate + ' TO ' + tdate)

    $.ajax({
      url:'../jquery/product-sales-report.php', 
      type:'post', 
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        $('table').removeClass('d-none')
        $('.card-body').removeClass('d-none')
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
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

<?php $title = "Customer report"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'Sales report'  ?>
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
                  <h1 class="page-title"> Customer sales <small>report</small> </h1>
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
                            <input type="hidden" name="cust" id='cust' value='true'>
                            <div class="form-group col-md-6">
                                <label for="">Payment status</label>
                                <select name="pstatus" id="pstatus" class="form-control">
                                    <option value="">ALL</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Not paid">Not paid</option>
                                    <option value="Partial payment">Partial</option>
                                    
                                </select>
                                
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Customer</label>
                                <select id='bss4'  name = 'customer' data-toggle='selectpicker' data-live-search='true' data-width='100%' >
                                <option data-tokens='' value=''> ALL  </option>
                                <?php 
                                    $stmt = "SELECT * FROM customer ORDER BY cust_name ASC";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['customer_id'];
                                        $name = $row['cust_name'];
                                        $phone = $row['cust_phone'];
                                        echo "
                                            <option data-tokens='$phone' value='$id'>$name  </option>

                                        ";
                                    }

                                ?>
                             
                            </select>

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
                               <h4 class='text-center'>Customer Sales Report  </h4>
                               <hr>
                                <!-- Your main content here -->
                                <div class="table-responsive">
                                    <table class="table table-bordered d-none ">
                                        <thead>
                                            <tr class=''>
                                                <th >#</th>
                                      
                                                <th style='min-width:250px;'>Customer name</th>
                                                <th>Total amount</th>
                                                <th>Paid amount</th>
                                                <th>Balance</th>
                                                
                                            </tr>
                                            
                                        </thead>
                                        <tbody id='res'>
                                           
                                        </tbody>
                                      
                                    </table>
                                    <br> <br>
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

    var customer = true;
    //From to date display
    $('#sr_fr_to').html(fdate + ' TO ' + tdate)
  

    $.ajax({
      url:'../jquery/reports/customer-sales.php', 
      type:'post', 
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        $('table').removeClass('d-none')
        $('.card-body').removeClass('d-none')
        $('#res').html(res)
        $('#frmShowRep')[0].reset();

       
      }
    })

   
  })


  
  
  
})

function PrintElem(elem) {
      Popup(jQuery(elem).html());
}

function Popup(data) {
  var mywindow = window.open('', 'invoice-print', 'height=595,width=842');
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
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
    body * {
        visibility: hidden;
    }
    .invoice-print, .invoice-print * {
        visibility: visible;
    }
    .invoice-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 20px;
    }
    .invoice-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .invoice-header img {
        max-height: 150px;
    }
    .invoice-header h2 {
        color: #333;
        margin: 10px 0;
    }
    .invoice-content {
        margin: 20px 0;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .table tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-danger {
        background-color: #dc3545;
        color: white;
    }
    .invoice-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
}

    </Style>

<?php $title = "Items Inventory Report"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'Items Inventory'  ?>
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
                  <h1 class="page-title"> Items <small>Inventory Report</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>items/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Items </a>
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
                        
                        <form class='row' method='post' id='frmShowItems'>
                            <div class="form-group col-md-3">
                                <label for="">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">ALL CATEGORIES</option>
                                    <?php 
                                        $stmt = "SELECT * FROM item_category ORDER BY category_name ASC";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                            $id = $row['itemcat_id'];
                                            $name = $row['category_name'];
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Stock Status</label>
                                <select name="stock_status" id="stock_status" class="form-control">
                                    <option value="">ALL ITEMS</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="low_stock">Low Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Unit</label>
                                <select name="unit" id="unit" class="form-control">
                                    <option value="">ALL UNITS</option>
                                    <?php 
                                        $stmt = "SELECT * FROM unit ORDER BY unit_name ASC";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                            $id = $row['unit_id'];
                                            $name = $row['unit_name'];
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Search Item</label>
                                <input type="text" name="search" id="search" placeholder="Search by item name..." class="form-control">
                            </div>

                            <div class="form-group col-md-12 my-3 text-center">
                                <center>
                                <button type="submit" class='btn btn-info btn-block col-md-4'>Show Items</button>
                                </center>
                            </div>
                            
                        </div>
                        
                        </form>

                        <div class="card-body d-none" id="reportContent">
                        <button id='printButton' class='btn btn-primary mb-3' onclick="PrintElem('contentToPrint')"> <i class="fa fa-print"></i> Print Report</button>
                        <div class="invoice-print" id='contentToPrint'>
                            <div class="invoice-header">
                               <center> <img src="<?= BASE_URL ?>assets/images/logo/ramaas_logo.jpg" height='200' alt="Ramaas Logo"></center>
                                <hr>
                                <h2>Items Inventory Report</h2>
                                <p>Generated on: <?= date('F d, Y H:i:s') ?></p>
                            </div>
                            <div class="invoice-content">
                                <div id="itemsTable">
                                    <!-- Items will be loaded here -->
                                </div>
                            </div>
                            <div class="invoice-footer">
                                <hr>
                                <p><strong>Ramaas Inventory Management System</strong></p>
                                <p>Report generated on <?= date('F d, Y') ?> at <?= date('H:i:s') ?></p>
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

  // Form submission
  $('#frmShowItems').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none');
    
    var category = $('#category').val();
    var stock_status = $('#stock_status').val();
    var unit = $('#unit').val();
    var search = $('#search').val();
    
    $.ajax({
      url: '../jquery/reports/items-inventory.php',
      type: 'POST',
      dataType: 'json',
      data: {
        category: category,
        stock_status: stock_status,
        unit: unit,
        search: search
      },
      success: function(response){
        $('.overlay').addClass('d-none');
        
        if(response.success){
          $('#itemsTable').html(response.html);
          $('#reportContent').removeClass('d-none');
          
          // Scroll to report
          $('html, body').animate({
            scrollTop: $('#reportContent').offset().top - 100
          }, 500);
        } else {
          toastr.error(response.message || 'Error loading items');
        }
      },
      error: function(xhr, status, error){
        $('.overlay').addClass('d-none');
        console.log('AJAX Error:', error);
        console.log('Response:', xhr.responseText);
        toastr.error("Network error. Please try again.");
      }
    });
  });

  // Print function
  function PrintElem(elem) {
    var mywindow = window.open('', 'items-report', 'height=595,width=842');
    mywindow.document.write('<html><head><title>Items Inventory Report</title>');
    mywindow.document.write('<link rel="stylesheet" href="../assets/stylesheets/theme.min.css" data-skin="default">');  
    mywindow.document.write('<link rel="stylesheet" href="../assets/vendor/bootstrap-select/css/bootstrap-select.min.css">');  
    mywindow.document.write('<style>body{font-family: Arial, sans-serif;} .invoice-print{margin:0;padding:20px;} .table{width:100%;border-collapse:collapse;} .table th, .table td{border:1px solid #ddd;padding:8px;text-align:left;} .table th{background-color:#f2f2f2;}</style>');
    mywindow.document.write('</head><body>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.focus();
    mywindow.document.close();
    mywindow.print();
  }

  // Make PrintElem globally available
  window.PrintElem = PrintElem;
  
  // Print button click handler
  $('#printButton').on('click', function(){
    PrintElem('contentToPrint');
  });
});

</script>

</body>
</html>

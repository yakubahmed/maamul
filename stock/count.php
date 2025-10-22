<?php include('../path.php'); ?>

<style>
  @media print {
    #dataTable thead tr th {
      /* Change the title/header text when printing */
      content: attr(data-print-title);
    }
  }
</style>

<?php $title = 'Stock count';  include(ROOT_PATH . '/inc/header.php'); ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">


      <?php $menu = 'Stock'  ?>
      
      <?php $smenu = 'Count'  ?>
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
                  <h1 class="page-title"> Stock Count </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <button  class="btn btn-info text-right" data-toggle="modal" data-target="#addAdjModal"> <i class="fa fa-plus"></i> Add new adjustment </button> -->
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">
                      
                      <div class="card-body ">
                        <table id="itemCount" class="table table-bordered  table-striped ">
                          <thead>
                          
                            <tr>
                              <th >#</th>
                              <th>Item name</th>
                              <th>Category</th>
                              <th>Purchase price</th>
                              <th>Sale price</th>
                              <th>Quantity</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              


                            <td>1</td>
                            <td>Iphone 15 Pro Max</td>
                            <td>Smart phones</td>
                            <td>1200 $</td>
                            <td>1500 $</td>
                            <td>5 Pc</td>
                           

                            </tr>
                               
                          </tbody>

                        </table>
              
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->

                      <div class="modal fade" id="editAdjModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay d-none">
                              <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalCenterLabel" class="modal-title"> Add Adjustment </h5>
                            </div><!-- /.modal-header -->
                            <form  method="post" id='' >
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="row" id='frmEditAdj'>
                           


                                </div>
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Update adjustment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                              </div><!-- /.modal-footer -->
                            </form>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->
             
 

              </div><!-- /.page-section -->

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

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.jss"></script>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });


})

$(document).ready(function() {
    $('#itemCount').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ],
        columnDefs: [ {
            targets: -1,
            visible: false
        } ]
    } );
} );

</script>
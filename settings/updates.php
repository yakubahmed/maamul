<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Updates';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Setting'  ?>
      
      <?php $smenu = 'Updates'  ?>
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
                  <h1 class="page-title"> Updates </h1>
                </div>
                <!-- <div class="col-md-6 text-right">
                    <button  class="btn btn-info text-right" data-toggle="modal" data-target="#addAdjModal"> <i class="fa fa-plus"></i> Add new adjustment </button>
                </div> -->
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="col-md-12">

                    <div class="card bg-light ">
                      
                      <div class="card-body ">
     
     
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->

                  <!-- Normal modal -->
                      <div class="modal fade" id="addAdjModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
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
                            <form  method="post" id='frmAddAdj'>
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="row">
                                  <div class="form-group col-md-12">
                                    <label for="">Item *</label>
                                    <select name="item" id="bss3" class="form-control customer" required data-toggle='selectpicker' required data-live-search='true' >
                                      <option data-tokens='' value=''>Select item  </option>
                                    
                                      <?php
                                        $stmt = "SELECT * FROM item ";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                          $id = $row['item_id'];
                                          $name = $row['item_name'];
                                          echo "
                                            <option data-tokens='$id' value='$id'>$name  </option>
  
                                          ";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="">Current stock</label>
                                    <p id='curr_stock'>-</p>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="">Quantity *</label>
                                    <input type="number" min='1' name="qty" id="qty" required class="form-control">
                                  </div>

                                  <div class="form-group col-md-12">
                                    <label for="">Adjustment type *</label>
                                    <select name="adjtype" id="adjtype" class="form-control" required>
                                        <option value="">Select Adjustment type</option>
                                        <option value="Add">Add</option>
                                        <option value="Subtract">Subtract</option>
                                    </select>
                                  </div>

                                  <div class="form-group col-md-12">
                                    <label for="">Date *</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                  </div>


                                </div>
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Save adjustment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                              </div><!-- /.modal-footer -->
                            </form>
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

                  <!-- Edit Modal -->
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


<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });



})

</script>
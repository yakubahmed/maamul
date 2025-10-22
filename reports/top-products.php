<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Top products report"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Warbixin'  ?>
      
      <?php $smenu = 'Top products report'  ?>
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
                  <h1 class="page-title"> Products <small>top</small> </h1>
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

                      <div class="card-header">
                        <h6>Top 10 Selling items</h6>

                      </div>
                      
                      <div class="card-body ">
                      <div class="col-md-12">
                  <!-- .card -->

              </div>

                            <div class="col-md-12">
                              <div class="table-responsive">
                                <table class='table'>
                                  <thead>
                                    <tr class='bg-primary text-light'>
                                      <th>#</th>
                                      <th style='min-width:200px;'>Item</th>
                                      <th style='min-width:140px;'>Category</th>
                                      <th style='min-width:100px;'>Total items sold</th>
                                      <th style='min-width:100px;'>Percentage</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                      $sql = "SELECT SUM(qty) FROM order_item ";
                                      $re = mysqli_query($con, $sql);
                                      $rw = mysqli_fetch_array($re);
                                      $total  = $rw[0];
                                      $i=0;
                                      
                                      $stmt = "SELECT item.item_name, item.item_image,item_category.category_name ,SUM(order_item.qty) 
                                      FROM item,item_category ,order_item WHERE item.item_id = order_item.item_id AND item.item_category = item_category.itemcat_id GROUP by item.item_id ORDER BY order_item.qty DESC LIMIT 10"; 
                                      $result = mysqli_query($con, $stmt);
                                      while($row = mysqli_fetch_array($result)){
                                        $i++;
                                        $name = $row[0];
                                        $items = array("text-info", "text-green", "text-yellow", "text-success", "text-black", "text-red", "text-blue", "text-cyan", "text-tail");
                                        $qty = $row[3];
                                        $img = $row[1];
                                        $cat = $row[2];
                                        $per = $qty / $total * 100;
                                        $per = number_format($per, 0);
                                        
                                        echo "
                                          <tr>
                                            <td>$i</td>
                                            <td> <img src='../assets/images/products/$img' height='30' alt=''> $name</td>
                                            <td>$cat</td>
                                            <td>$qty</td>
                                            <td>$per %</td>
                                          </tr>
                                        ";

                                      } 
                                ?>
                                  </tbody>
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
      url:'../jquery/sales-report.php', 
      type:'post', 
      data: $('#frmShowRep').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        $('table').removeClass('d-none')
        $('#res').html(res)
        $('#frmShowRep')[0].reset();

        console.log(res)
      }
    })
  })



})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Premission"; include(ROOT_PATH . '/inc/header.php'); ?>
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
                  <h1 class="page-title"> Premission </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="<?= BASE_URL ?>user/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View Users </a> -->
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
                            <form method='post' id='frmPre' class="row">
                              <div class="col-md-10">
                                 
                                  <select name="role" id="role" class="form-control" required>
                                    <option value="">Select role</option>
                                    <?php 
                                      $stmt = "SELECT * FROM usergroup WHERE group_name != 'admin'";
                                      $result = mysqli_query($con, $stmt);
                                      while($row = mysqli_fetch_assoc($result)){
                                        $id = $row['group_id'];
                                        $name = $row['group_name'];
                                        echo "
                                          <option value='$id'>$name</option>
                                        ";
                                      }
                                    ?>
                                  </select>
                              </div>
                              <div class="col-md-2">
                                  <button type="submit" class="btn btn-info btn-block">Submit</button>
                              </div>
                            </form> <!--Row -->

                            <div class="table-responsive">
                                <table class="table d-none">
                                    <thead>
                                        <tr class='bg-success text-light'>
                                            <th>#</th>
                                            <th style='min-width:250px'>Module</th>
                                            <th style='min-width:250px'>Specific premissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Sale</td>
                                            <td>
                                                <span class='badge badge-secondary'>
                                                View <input type="checkbox" name="" id="">
                                                </span>

                                                <span class='badge badge-secondary'>
                                                Add <input type="checkbox" name="" id="">
                                                </span>

                                                <span class='badge badge-secondary'>
                                                Edit <input type="checkbox" name="" id="">
                                                </span>

                                                <span class='badge badge-secondary'>
                                                Delete <input type="checkbox" name="" id="">
                                                </span>



                                                
                                                
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success text-center col-md-3">Save changes</button>
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

  $('#frmPre').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    
    $.ajax({
      url:'../jquery/premission.php', 
      type:'post', 
      data: $('#frmPre').serialize(),
      success: function(res){
        $('.overlay').addClass('d-none')
        toastr.success("Permissions updated successfully!")
        $('table').removeClass('d-none')
        $('tbody').html(res)
        console.log(res)
      },
      error: function(){
        $('.overlay').addClass('d-none')
        toastr.error("Error updating permissions!")
      }
    })
  })

  $(document).on('keyup', '#email', function(){
    $('#email').removeClass('is-invalid')
  })

  $(document).on('keyup', '#phone', function(){
    $('#phone').removeClass('is-invalid')
  })


})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Backup and recovery';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Settings'  ?>
      
      <?php $smenu = 'Backup and recovery'  ?>
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
                  <h1 class="page-title"> Backup <small>and recovery</small> </h1>
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
                      
                      <div class="card-body py-4 ">

                      <div class="card">
                        <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"> </i> 
                        </div>
                        <div class="card-header bg-primary text-light">Create new backup</div>
                        <div class="card-body">
                          <form  method="post" id='frmBackup'>
                            <div class="form-group col-md-12">
                              <input type="hidden" name="backup" id='backup' value='true'>
                              <button type="submit" class='btn btn-outline-primary'  id='btncbup'> Create a backup </button>
                            </div>

                            <div class="form-group col-md-12 download d-none">
                              <h3>Your backup file is ready</h3>
                              <a href="<?= BASE_URL ?>maamul_backup.sql" class="btn btn-primary" target='_BLANK' id='down'>Download now</a>
                            </div>
                          </form>
                        </div>
                      </div>
     
                      <div class="card">
                        <div class="overlay1 d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"> </i> 
                        </div>
                        <div class="card-header bg-success text-light">Make a recovery</div>
                        <div class="card-body">
                          <form method="post"  enctype='multipart/form-data' id='frmRecover'>
                            <div class="form-group col-md-12">
                              <label for="">Upload recovery file</label>
                              <input type="hidden" name="recover" id='recover' value='true'>
                              <input type="file" name="rfile" id="rfile" accept='.sql' class="form-control" required>
                            </div>
  
                            <div class="form-group col-md-12">
                              <button type="submit" class="btn btn-outline-success"> Recover now</button>
                            </div>
                          </form>

                        </div>
                      </div>
     
          
                      </div>
                    </div>
                  </div>

                </div><!-- /.section-block -->

                
 

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


  //Creating a new backup \
  $(document).on('submit', '#frmBackup', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    
    $.ajax({
      url:'../jquery/settings/backup-recovery.php', 
      type:'post', 
      data: { new_backup: true },
      success: function(res){
        if(res == 'completed'){
          setTimeout(() => {
            toastr.success("Your backup file is ready");
            $('.overlay').addClass('d-none')
            $('#btncbup').addClass('d-none')
            $('.download').removeClass('d-none')
            
          }, 1500);

        }else{
          toastr.error("Something is wrong please try again");
          $('.overlay').addClass('d-none')
          console.log(res)
        }
      }
    })


  })

  $(document).on('click', '#down' , function(){
    setTimeout(() => {
      location.reload();
    }, 2500); 
  })

  //Recovering database 
  $(document).on('submit', '#frmRecover', function(e){
    e.preventDefault();
    $('.overlay1').removeClass('d-none')


    $.ajax({
      url:'../jquery/settings/backup-recovery.php', 
      type:'post', 
      cache: false,
      contentType: false, // you can also use multipart/form-data replace of false
      processData: false,
      data: new FormData(this),
      success: function(res){
        if(res == 'completed'){
          setTimeout(() => {
            toastr.success("Your database is recovered successfully");
            $('.overlay1').addClass('d-none')
            location.reload();
            
          }, 1500);

        }else{
          toastr.error("Something is wrong please try again");
          $('.overlay1').addClass('d-none')
          console.log(res)
        }
      }
    })

  })



})

</script>
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
                    <div class="card bg-light">
                      <div class="card-body">
                        <h5><?= __t('Updates') ?></h5>
                        <p class="text-muted mb-3"><?= __t('Check for updates') ?>. We will verify your network connection first. If no updates are available, you will see a clear message.</p>

                        <div id="update_status" class="mb-2"></div>

                        <div id="update_actions" class="mb-3">
                          <button type="button" id="btn_check_updates" class="btn btn-info">
                            <i class="fa fa-sync"></i> <?= __t('Check for updates') ?>
                          </button>
                          <button type="button" id="btn_apply_updates" class="btn btn-success d-none">
                            <i class="fa fa-download"></i> <?= __t('Update now') ?>
                          </button>
                        </div>

                        <!-- Details hidden for a simpler, user‑friendly view -->
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

  function setStatus(html){
    $('#update_status').html(html);
  }

  function renderCommits(commits){
    var $list = $('#commit_list');
    $list.empty();
    commits.forEach(function(c){
      var item = '<li><code>'+c.hash+'</code> - '+$('<div>').text(c.subject).html()+' <span class="text-muted">('+c.when+')</span></li>';
      $list.append(item);
    });
  }
  

  $('#btn_check_updates').on('click', function(){
    checkUpdatesFriendly();
  });

  $('#btn_apply_updates').on('click', function(){
    applyUpdatesFriendly();
  });

  function checkUpdatesFriendly(){
    setStatus('<span class="text-info"><i class="fa fa-sync fa-spin"></i> Checking for updates...</span>');
    $('#btn_apply_updates').addClass('d-none');
    $.ajax({
      url:'../jquery/updates.php',
      type:'post',
      dataType:'json',
      data:{check:true},
      success:function(res){
        if(res && res.error){
          if(res.error === 'no_network'){
            setStatus('<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> No internet connection. Please try again.</span>');
          }else if(res.error === 'not_git_repo'){
            setStatus('<span class="text-warning"><i class="fa fa-info-circle"></i> Update checks are not available for this installation.</span>');
          }else if(res.error === 'git_unavailable'){
            setStatus('<span class="text-warning"><i class="fa fa-info-circle"></i> Update tools are not available on this server.</span>');
          }else if(res.error === 'shell_disabled'){
            setStatus('<span class="text-warning"><i class="fa fa-info-circle"></i> Server restrictions prevent checking for updates.</span>');
          }else if(res.error === 'not_logged_in'){
            toastr.error('Session expired. Please login again.');
            setTimeout(function(){ window.location.href = '<?= BASE_URL ?>login.php'; }, 1500);
          }else{
            setStatus('<span class="text-danger">'+(res.message||'Unable to check for updates.')+'</span>');
          }
          return;
        }
        if(res && res.behind > 0){
          setStatus('<span class="text-success"><i class="fa fa-check"></i> An update is available. Click "Update now" to install.</span>');
          $('#btn_apply_updates').removeClass('d-none');
        }else{
          setStatus('<span class="text-muted"><i class="fa fa-minus-circle"></i> No updates available. You are up to date.</span>');
        }
      },
      error:function(xhr){
        console.log('AJAX Error:', xhr.responseText);
        setStatus('<span class="text-danger">Network error. Please try again.</span>');
      }
    });
  }

  function applyUpdatesFriendly(){
    setStatus('<span class="text-info"><i class="fa fa-download"></i> Installing updates...</span>');
    $.ajax({
      url:'../jquery/updates.php',
      type:'post',
      dataType:'json',
      data:{apply:true},
      success:function(res){
        if(res && res.success){
          toastr.success('Updated successfully');
          checkUpdatesFriendly();
        }else if(res && res.error === 'no_network'){
          setStatus('<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> No internet connection. Please try again.</span>');
        }else if(res && res.error === 'shell_disabled'){
          setStatus('<span class="text-warning"><i class="fa fa-info-circle"></i> Server restrictions prevent applying updates.</span>');
        }else if(res && res.error === 'not_git_repo'){
          setStatus('<span class="text-warning"><i class="fa fa-info-circle"></i> Updates cannot be applied for this installation.</span>');
        }else if(res && res.error === 'not_logged_in'){
          toastr.error('Session expired. Please login again.');
          setTimeout(function(){ window.location.href = '<?= BASE_URL ?>login.php'; }, 1500);
        }else{
          setStatus('<span class="text-danger">'+((res && res.message) || 'Failed to apply updates.')+'</span>');
        }
      },
      error:function(xhr){
        console.log('AJAX Error:', xhr.responseText);
        setStatus('<span class="text-danger">Network error. Please try again.</span>');
      }
    });
  }

  // Only check when the button is clicked
})

</script>




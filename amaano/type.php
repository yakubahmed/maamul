<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "New Amaano"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->

      <?php $menu = 'Amaano'  ?>
      
      <?php $smenu = 'Nooca amaanada'  ?>
      
      
      
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
                  <h1 class="page-title"> Nooca amaanada </h1>
                </div>
                <div class="col-md-6 text-right">
                    <!-- <a href="<?= BASE_URL ?>amaano/list" class="btn btn-outline-info text-right"> <i class="fa fa-users"></i> Liiska nooca amaanada </a> -->
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                    <div class="row">
                        <div class="col-md-4">
      
                          <div class="card ">
                            <div class="overlay d-none load1">
                                <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
      
                            <div class="card-header bg-light">
                              Diiwan gelin
                            </div>
                            
                            <div class="card-body ">
                              
                              <form class='row' method='post' id='frmAmanoT'>
      
                                  <div class="form-group col-md-12">
                                      <label for="">Magaca </label>
                                      <input type="text" name="atname" id="atname" class="form-control">
                                  </div>
      
                                  <div class="form-group col-md-12">
                                      <label for="">Faah faahin</label>
                                      <textarea name="cesc" class='form-control' id="desc" ></textarea>
                                  </div>
                          
      
                                <div class='form-group col-md-12'>
                                  <center>
                                    <button type='submit' class='btn btn-info rounded-6'>Ii keydi xogta</button>
                                  </center>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card  ">
                                <div class="overlay d-none">
                                    <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                                </div>
                        
                                <div class="card-body ">
                                    <table id="example1" class="table table-bordered  table-striped ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nooca amaanada</th>
                                            <th>Faah faahin</th>>
                                            <th>Taarikh</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 0;
                                            $stmt = "SELECT * FROM amaano_type ORDER BY created_date DESC"; 
                                            $result = mysqli_query($con, $stmt);
                                            while($row = mysqli_fetch_assoc($result)){
                                                $count ++;
                                                $name =  $row['name'];
                                                $desc =  $row['description'];
                                                $date = date('d/m/Y', strtotime($row['created_date'])) ;
                                                $id =  $row['amanotid'];
                                                if(empty($name)){ $name = 'N/A'; }

                                                echo "
                                                    <tr>
                                                        <td>$count</td>
                                                        <td>$name</td>
                                                        <td>$desc</td>
                                                        <td>$date</td>
                                                        <td>
                                                            <div class='dropdown d-inline-block'>
                                                                <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                                                <div class='dropdown-menu dropdown-menu-right' style=''>
                                                                    <div class='dropdown-arrow'></div>
                                                                    <button type='button' data-toggle='modal' data-target='#editAmanot' id='get_atype' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Amaano Type</button>
                                                                    <button type='button' class='dropdown-item btn-danger' id='del_atype' data-id='$id'><i class='fa fa-trash'></i> Delete Amaano Type</button>
                                                                </div>
                                                            </div>
                                                        </td>
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


    <!-- Models -->

    <!-- Add customer drawer -->
    <div class="modal modal-drawer fade" id="editAmanot" tabindex="-1" role="dialog" aria-labelledby="editAmanot" aria-hidden="true">
            <!-- .modal-dialog -->
            <div class="modal-dialog modal-lg modal-drawer-right" role="document">
              <!-- .modal-content -->
              <div class="modal-content bg-light">

                <!-- .modal-header -->
                <div class="modal-header">
                  <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Dib u sixid nooca<small> amaanada</small> </strong> </h5>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body" >
                
                <form class='row' method='post' id='frmEamanoT'>

                      <div class='form-group col-md-12'>
                          <label for=''>Magaca </label>
                          <input type='text' name='atname' id='atname' class='form-control'>
                      </div>

                      <div class='form-group col-md-12'>
                          <label for=''>Faah faahin</label>
                          <textarea name='cesc' class='form-control' id='desc' ></textarea>
                      </div>
                

                      <div class='form-group col-md-12'>
                        <center>
                          <button type='submit' class='btn btn-info rounded-6'>Ii keydi xogta</button>
                        </center>
                      </div>
                </form>

                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div><!-- /.modal-footer -->
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });

  $('#frmAmanoT').on('submit', function(e){
    e.preventDefault();
    $('.load1').removeClass('d-none')
    $.ajax({
      url:'../jquery/amaano/type.php', 
      type:'post', 
      data: $('#frmAmanoT').serialize(),
      success: function(res){
        if(res === 'success'){
          toastr.success("Amaano type added successfully!");
          $('#frmAmanoT')[0].reset();
          $('.load1').addClass('d-none');
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          toastr.error("Error adding amaano type!");
          $('.load1').addClass('d-none');
        }
      },
      error: function(){
        toastr.error("Error adding amaano type!");
        $('.load1').addClass('d-none');
      }
    })
  })


  //

  $(document).on('click', '#get_atype', function(){
    var id = $(this).data('id')
    $.ajax({
      url:'../jquery/amaano/type.php', 
      type:'post', 
      data: { get_amanot: id },
      success: function(res){
        $('#frmEamanoT').html(res)
        
      }
    })
  });


  $(document).on('submit', '#frmEamanoT', function(e){
    e.preventDefault();
    $.ajax({
      url:'../jquery/amaano/type.php', 
      type:'post', 
      data: $('#frmEamanoT').serialize(),
      success: function(res){
        if(res === 'success'){
          toastr.success("Amaano type updated successfully!");
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          toastr.error("Error updating amaano type!");
        }
      },
      error: function(){
        toastr.error("Error updating amaano type!");
      }
    })
  });


     //Delete product
     $(document).on('click', '#del_atype', function(){
        var id = $(this).data('id');

            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url:'../jquery/amaano/type.php',
                  type:'post',
                  data: 'del_atype='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Category deleted successfully.',
                        'success'
                      )
                      location.reload();
                    }else{
                      Swal.fire(
                        'Error!',
                        'Something is wrong please try again.',
                        'error'
                      )
                      
                    }
                  }
                })

              }
            })
      })



})

</script>
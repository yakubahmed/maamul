<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Delivery method"; include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Delivery note'  ?>
      
      <?php $smenu = 'Delivery method'  ?>
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
                  <h1 class="page-title"> Delivery  <small>method</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button  class="btn btn-info text-right" data-toggle="modal" data-target="#addDelModal"> <i class="fa fa-plus"></i> Add new method </button>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                  <div class="row">
     
                    <div class="col-md-12">
                      <div class="card bg-light">
                      <div class="card-body ">
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Delivery method name</th>
                              <th>Description</th>
                              <th>Date</th>
                              <th></th>
                             
                            </tr>
                          </thead>
                          <tbody>
                          
                             <?php 
                              $i = 0;
                              $stmt = "SELECT * FROM delivery_method ";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $i++;
                                $id = $row['del_meth_id'];
                                $name = $row['meth_name'];
                                $des = $row['description'];
                                $date = date("M d, Y", strtotime($row['date']));
                                if($des == "" ){ $des = "N/A"; }

                                echo "
                                  <tr>
                                    <td>$i</td>
                                    <td>$name</td>
                                    <td>$des</td>
                                    <td>$date</td>
                                    <td>
                                      <div class='dropdown d-inline-block'>
                                        <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                        <div class='dropdown-menu dropdown-menu-right' style=''>
                                          <div class='dropdown-arrow'></div>
                                          <button type='button' data-toggle='modal' data-target='#editDelModal' id='edit_del' data-id='$id' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Delivery Method</button>
                                          <button type='button' class='dropdown-item btn-danger' id='del_cat' data-id='$id'><i class='fa fa-trash'></i> Delete Delivery Method</button>
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


<!-- Modal -->
<div class="modal fade" id="addDelModal" data-controls-modal="addDelModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addDelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form  method="post" id='frmAddDel'>
            <div class="modal-header">
              <h5 class="modal-title" id="addDelModalLabel">Add delivery <small>method</small></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
      
             <div class="form-group col-md-12">
              <label for="">Method name *</label>
              <input type="text" name="mname" id="mname" required autocomplete='off' class="form-control">
             </div>
      
             <div class="form-group col-md-12">
              <label for="">Description</label>
              <textarea name="desc" id="desc" class='form-control'></textarea>
             </div>
      
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editDelModal" data-controls-modal="editDelModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addDelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form  method="post" id='frmEditDel'>
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit delivery <small>method</small></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id='res'>
      
             <div class="form-group col-md-12">
              <label for="">Method name *</label>
              <input type="text" name="mname1" id="mname1" required autocomplete='off' class="form-control">
             </div>
      
             <div class="form-group col-md-12">
              <label for="">Description</label>
              <textarea name="desc1" id="desc1" class='form-control'></textarea>
             </div>
      
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
  </div>
</div>
<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>

<script>

$(document).ready(function(){
  var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
  });



  $('#frmAddDel').on('submit', function(e){
    e.preventDefault();

    $.ajax({
      url:'../jquery/delivery-method.php', 
      type:'post', 
      data: $('#frmAddDel').serialize(),
      success: function(res){
        if(res == 'found_meth'){
          toastr.error("This Method name is allready registered, try another one.")
          $('.overlay').addClass('d-none')
          $('#mname').addClass('is-invalid')
        }else if (res == 'success'){
          toastr.success("delivery method registered successfully")
          $('#frmAddDel')[0].reset();
          $('.overlay').addClass('d-none')
          $("#addDelModal").hide();
          $('#mname').removeClass('is-invalid')
          location.reload()
        }
      }
    })
  })


  function get_categories(){
   
    $.ajax({
      url:'../jquery/item-category.php', 
      type:'post', 
      data: {all_categores: true},
      success: function(res){
        $('tbody').html(res)
      }
    });
  }

      //Delete product
      $(document).on('click', '#del_cat', function(){
        var id = $(this).data('id');
        console.log(id)
        console.log(id)
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
                  url:'../jquery/delivery-method.php',
                  type:'post',
                  data: 'del_cat='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Delivery method deleted successfully.',
                        'success'
                      )
                      location.reload()
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
    
    $(document).on('click', '#edit_del', function(e){
      e.preventDefault();
       var id = $(this).data('id')
      $.ajax({
          url:'../jquery/delivery-method.php',
          type:'post',
          data: 'edit_cat='+id,
          success: function(data){
            
            $('#res').html(data)
          }
                  
      })
    })

  $('#frmEditDel').on('submit', function(e){
    e.preventDefault()
    $.ajax({
          url:'../jquery/delivery-method.php',
          type:'post',
          data: $('#frmEditDel').serialize(),
          success: function(data){
            if(data == 'updated'){
              $('#frmEditDel')[0].reset();
              $('#frmEditDel').addClass('d-none')
              $('#frmAddDel').removeClass('d-none')
              $('#mname1').removeClass('is-invalid')
              toastr.success("Updated Successfully")
              setTimeout(() => {
                location.reload();
              }, 1000);
              
              
            }else if(data == "found"){
              toastr.error("This delivery method name is allready registered please try again")
              $('#mname1').addClass('is-invalid')

            }else{
              toastr.error("Something is wrong please try again")

            }

          }
                  
      })
  })

})

</script>
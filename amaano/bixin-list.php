<?php include('../path.php'); ?>

<?php $title = 'Amaano list';  include(ROOT_PATH . '/inc/header.php'); ?>

      <?php $menu = 'Amaano'  ?>
      
      <?php $smenu = ''  ?>
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
                  <h1 class="page-title"> Liiska amanada <small>la bixiyay</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>amaano/bixin" class="btn btn-outline-info text-right"> <i class="fa fa-plus"></i> Diwaan geli amaano bixin </a>
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
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Macaamiilka </th>
                              <th>Sheyga</th>
                              <th>Tirada la siiyay</th>
                              <th>Tirada hartay</th>
                              <th>Tirada guud</th>
                              <th>Faahfaahin</th>
                              <th>Taarikh</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php 
                            $count = 0;
                            $stmt = "SELECT bixin_id, amount, amaano_bixin.description as 'desc', date, customer.cust_name, amaano.name, amaano.total, amaano.given, amaano.remain
                            FROM amaano_bixin, customer, amaano WHERE amaano_bixin.amaano_id = amaano.amano_id AND amaano_bixin.amaano_id IN (SELECT amaano_id FROM amaano WHERE amaano.customer_id = customer.customer_id)";
                            $result = mysqli_query($con, $stmt); 
                            while($row = mysqli_fetch_assoc($result)){
                              $count++;
                              $id = $row['bixin_id'];
                              $amount = $row['amount'];
                              $desc = $row['desc'];
                              $date = date('d/m/Y', strtotime($row['date'])) ;
                              $cust = $row['cust_name'];
                              $amaano = $row['name'];
                              $total = $row['total'];
                              $given = $row['given'];
                              $bal = $row['remain'];

                              echo "
                                <tr>
                                  <td>$count</td>
                                  <td>$cust</td>
                                  <td>$amaano</td>
                                  <td>$given</td>
                                  <td>$bal</td>
                                  <td>$total</td>
                                  <td>$desc</td>
                                  <td>$date</td>
                                  <td>
                                    <div class='dropdown d-inline-block'>
                                      <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                      <div class='dropdown-menu dropdown-menu-right' style=''>
                                        <div class='dropdown-arrow'></div>
                                        <button type='button' class='dropdown-item btn-danger' id='delAmaano' data-id='$id'><i class='fa fa-trash'></i> Tir amaanada</button>
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

                </div><!-- /.section-block -->
             


                      <!-- Edit amaano drawer -->
                      <div class="modal modal-drawer fade" id="editABixin" tabindex="-1" role="dialog" aria-labelledby="editABixin" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            <div class="overlay " id='loadedit'>
                              <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                            </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Wax ka badalid amaano <small>Bixin</small></strong> </h5>
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='row' method='post' id='frmAmano'>
                                  <div class='form-group col-md-6'>
                                    <label for=''> Macaamiil</label>
                                      <div class="input-group input-group-alt"> 
                                        <select name="customer" id="customer" class="form-control customer" data-toggle='selectpicker' required data-live-search='true' >
                                          <option data-tokens='' value=''> -- Dooro macaamiil -- </option>
                              
                                        </select>
                                      
                                      </div>
                                      <p class='text-danger d-none' id='custMsg'></p>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="">Sheyga amaanada </label>
                                    <div class="input-group input-group-alt"> 
                                        <select name="amanoitem" id="amanoitem" class="form-control amanotype" data-toggle='selectpicker' required data-live-search='true' >
                                          <option data-tokens='' value=''>-- Dooro sheyga --  </option>
                                  
                                        </select>
                                      
                                      </div>
                                
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label for="">Tirada la bixiyay</label>
                                    <input type="number" name="given" id="given" step='0.02' class="form-control">
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label for="">Taarikhda la bixiyay</label>
                                    <input id="flatpickr03" type="hidden" placeholder="-- Dooro -- " required name='adate' id='adate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label for="">Tirada hartay</label>
                                    <input type="hidden" name="bal" id='bal'>
                                    <input type="text" name="balance" id="balance" class="form-control" readonly>
                                  </div>

                                  <div class="form-group col-md-12">
                                    <label for="">Faah faahin</label>
                                    <textarea name="desc" id="desc" class='form-control'></textarea>
                                  </div>

                                  <div class='form-group col-md-12'>
                                    <center>
                                      <button type='submit' class='btn btn-info rounded-6'>Bixi amaanada</button>
                                    </center>
                                  </div>
                                </form>
                              </div>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class='modal-footer'>
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                      </div><!-- /.modal -->

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



      //Delete product
      $(document).on('click', '#delAmaano', function(){
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
                  url:'../jquery/amaano/bixin.php',
                  type:'post',
                  data: 'delABixin='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Waa lagu guuleystay in la tiro xogta.',
                        'success'
                      )
                     setTimeout(() => {
                      location.reload();
                     }, 2500); 
                    }else{
                      Swal.fire(
                        'Error!',
                        'Waan ka xunahay xogtan lama tiro karo.',
                        'error'
                      )
                      
                    }
                  }
                })

              }
            })
      })
   
      /// Get Amaano information 
      $(document).on('click', '#btnEamaano', function(){
        var id = $(this).data('id')
        console.log(id)
        $.ajax({
          url:'../jquery/amaano/bixin.php', 
          type:'post', 
          data: 'editAmaanoInfo='+id ,
          success: function(res){
           $('#frmAmano').html(res)
           $('#loadedit').addClass('d-none')
          }
        })
      });

      // Updationg amano information
      $('#frmAmano').on('submit', function(e){
        e.preventDefault();
        $('#loadedit').removeClass('d-none')
        $.ajax({
            url:'../jquery/amaano/list.php',
            type:'post',
            data: $('#frmAmano').serialize(),
            success: function(res){
               if(res == "success"){
                $('#loadedit').addClass('d-none')
                toastr.success("Waala keydiyay xogta")
                $('#frmAmano')[0].reset();
                $('#editAmaanoDetail').modal('hide');

                setTimeout(() => {
                  window.location.replace("<?= BASE_URL ?>amaano/bixin-list");
                  
                }, 2500);

               }

            }
        });
      })



})

</script>
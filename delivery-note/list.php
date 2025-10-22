<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Delivery note list';  include(ROOT_PATH . '/inc/header.php'); ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->
      
            <?php $menu = 'Delivery note'  ?>
      
      <?php $smenu = 'Delivery note list'  ?>

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
                  <h1 class="page-title"> Delivery not <small>list</small> </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>delivery-note/add" class="btn btn-info text-right"> <i class="fa fa-user-plus"></i> Add delivery note </a>
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
                      <?php 
                        function total_items_unit($id){
                          global $con;
                          $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
                          $result = mysqli_query($con, $stmt);
                          $row = mysqli_fetch_array($result);
                          return $row[1];
                        }

                        function del_items_unit($id){
                          global $con;
                          $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
                          $result = mysqli_query($con, $stmt);
                          $row = mysqli_fetch_array($result);
                          return $row[1];
                        }
                        function balance_unit($id){
                          global $con;
                          $stmt = "SELECT SUM(qty), unit.shortname FROM del_note_item, unit WHERE unit.unit_id IN (SELECT unit_id FROM item WHERE item.item_id IN (select item_id FROM del_note_item WHERE del_note_item.del_note_id = $id))";
                          $result = mysqli_query($con, $stmt);
                          $row = mysqli_fetch_array($result);
                          return $row[1];
                        }

                        function total_items($id){
                            global $con;
                            $stmt = "SELECT SUM(qty) FROM del_note_item WHERE del_note_id  = $id";
                            $result = mysqli_query($con, $stmt);
                            $row = mysqli_fetch_array($result);
                            return $row[0];
                        }
                        function del_items($id){
                            global $con;
                            $stmt = "SELECT SUM(delivered) FROM del_note_item WHERE del_note_id  = $id";
                            $result = mysqli_query($con, $stmt);
                            $row = mysqli_fetch_array($result);
                            return $row[0];
                        }
                        function balance($id){
                            global $con;
                            $stmt = "SELECT SUM(balance) FROM del_note_item WHERE del_note_id  = $id";
                            $result = mysqli_query($con, $stmt);
                            $row = mysqli_fetch_array($result);
                            return $row[0];
                        }
                      ?>
                      
                      <div class="card-body  ">
                        <table id="example1" class="table table-bordered  table-striped ">
                          <thead>
                            <tr>
                              <th style='min-width:80px'>Invoice No</th>
                              <th style='min-width:120px'>Date</th>
                              <th style='min-width:200px'>Customer</th>
                              <th>Total items</th>
                              <th>Del items</th>
                              <th>Outstanding</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                              $stmt = "SELECT del_note.del_note_id, del_note.invoice_number,del_note.despatch_date,del_note.tran_date, delivery_method.meth_name, customer.cust_name, customer.cust_phone FROM del_note, delivery_method, customer 
                              WHERE del_note.cust_id = customer.customer_id AND del_note.del_method = delivery_method.del_meth_id ORDER BY del_note.del_note_id DESC ";
                              $result = mysqli_query($con, $stmt);
                              while($row  = mysqli_fetch_assoc($result)){
                                $id =  $row['invoice_number'];
                                $del_id  = $row['del_note_id'];
                                $date = date('d-m-Y', strtotime($row['tran_date']));
                                $customer = $row['cust_name'];
                                $total_unit = total_items_unit($row['del_note_id']);
                                $del_items_unit = del_items_unit($row['del_note_id']);
                                $balance_unit = balance_unit($row['del_note_id']);
                                $total =  total_items($row['del_note_id']);
                                $del =  del_items($row['del_note_id']);
                                $balance =  balance($row['del_note_id']);
                                $did = $row['del_note_id'];
                                echo "
                                    <tr>
                                        <td>$id</td>
                                        <td>$date</td>
                                        <td>$customer</td>
                                        <td class='text-center'>$total $total_unit</td>
                                        <td class='text-center'>$del $del_items_unit</td>
                                        <td class='text-center'>$balance $balance_unit</td>
                                        <td>
                                        <div class='dropdown d-inline-block'>
                                        <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                        <div class='dropdown-menu dropdown-menu-right' style=''>
                                          <div class='dropdown-arrow'></div>
                                          <button type='button' data-toggle='modal' data-target='#orderDetailModal' id='sale_detail' data-id='$del_id' class='dropdown-item'> <i class='fa fa-eye'></i> View </button> 
                                          <a href='" . BASE_URL . 'delivery-note/edit?id='. "$did' type='button' class='dropdown-item'> <i class='fa fa-edit'></i> Edit </a>
                                        
                                          <button type='button' class='dropdown-item btn-danger' id='btnDelSale' data-id='$del_id'><i class='fa fa-trash'></i> Delete </button>
                                          <a  href='" . BASE_URL . 'delivery-note/download?ref='. "$did' class='dropdown-item btn-info' id='' data-id='$del_id'><i class='fa fa-download'></i> Download</a>
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
             


              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">

                            <!-- .modal-header -->
                            <div class="modal-header bg-light">
                            
                                <div class="col-md-6">
                                  <h5 id="exampleModalDrawerRightLabel" class="modal-title "> <strong>Delivety note  <small>detail</small> </strong> </h5>
                                </div>
                                <div class="col-md-6 text-right">
                                  <!-- <button class="btn btn-success" type='button'> <i class="fa fa-download"></i> Download </button> -->
                                </div>
                          
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            
                              <div class="">
                                <form class='saleDetailP row' method='post' id='saleDetailP' >
                            
                                </form>
                              </div>

                            </div><!-- /.modal-body -->
                            <!-- .modal-footer -->
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                            </div><!-- /.modal-footer -->
                          </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                    <!-- Normal modal -->
                    <div class="modal fade" id="addPayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongLabel" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalLongLabel" class="modal-title"> Add Payment </h5>
                            </div><!-- /.modal-header -->

                            <form  method="post" class="" id='frmAddPay'>
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="row">
                                  <div class="form-group col-md-6">
                                    <label for="">Payment method *</label>
                                    <select name="pmeth" id="bss4" class="form-control pmeth" data-toggle='selectpicker' required data-live-search='true' >
                                      <option data-tokens='' value=''>Select payment method  </option>
                                      <?php 
                                        $stmt = "SELECT * FROM account";
                                        $result = mysqli_query($con, $stmt);
                                        while($row = mysqli_fetch_assoc($result)){
                                          $id = $row['account_id'];
                                          $name = $row['account_name'];
                                          $num = $row['account_number'];
                                          echo "
                                            <option data-tokens='$num' value='$id'> $name - $num  </option>
                                          
                                          ";
                                        }
                                      
                                      ?>
                                    </select>
                                  </div>
  
                                  <div class="form-group col-md-6">
                                    <label for="">Date *</label>
                                    <input id="flatpickr03" type="hidden" placeholder="Select sale date" name='sdate' id='sdate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                                    
                                  </div>
  
                                  <div class="form-group col-md-6">
                                    <label for="">Amount *</label>
                                    <input type="hidden" name="tamount" id='tamount'>
                                    <input type="number" autocomplete='off' name="amount" id="amount" required step='0.01' class="form-control" placeholder='0.00'>
                                    <p class='text-info' id='maxAmount'>The maximum amount you can pay is <strong>0.00 $</strong></p>
                                  </div>
  
                                  <div class="form-group col-md-6">
  
                                    <label for="">Balance</label>
                                    <input type="hidden" name="order" id='order'>
  
                                    <input type="number" name="balance" id="balance" readonly step='0.01' class="form-control" placeholder='0.00'>
                                  </div>
  
                                  <div class="form-group col-md-12">
                                    <label for="">Description</label>
                                    <textarea name="desc" id="desc" placeholder='Description goes here...' class='form-control'></textarea>
                                  </div>

                                </div>
                                                           
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-info">Save Payment</button>
                              </div><!-- /.modal-footer -->

                            </form>
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
      $(document).on('click', '#btnDelSale', function(){
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
                  url:'../jquery/delivery.php',
                  type:'post',
                  data: 'btnDelSale='+id,
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
                      console.log(data)
                      
                    }
                  }
                })

              }
            })
      })




      // Displaying sale detail
      $(document).on('click', '#sale_detail', function(){
        var id = $(this).data('id')
        $.ajax({
            url:'../jquery/delivery.php',
            type:'post',
            data: 'viewSingleDel='+id,
            success: function(data){
                $('#saleDetailP').html(data)
                //console.log(data)
                

            }
        });
      })

      // Edit customer - displaying information
      $(document).on('click', '#edit_acc', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/sale-history.php',
            type:'post',
            data: 'editSingleAcc='+id,
            success: function(data){
                $('.editAccPlace').html(data)
                //$('.overlay').addClass('d-none');

            }
        });
      })

    
      //Add Payment 
      $(document).on('click', '#btnAddPay', function(){
        var id = $(this).data('id')
        $('#order').val(id)

        $.ajax({
            url:'../jquery/sale-history.php',
            type:'post',
            data: 'addPayAmount='+id,
            success: function(data){
                $('#maxAmount').html('The maximum amount you can pay is <strong> ' + data + '</strong>')
                //$('.overlay').addClass('d-none');

              $('#tamount').val(data)
            }
        });

        
      })


      $('#frmAddPay').on('submit', function(e){
        e.preventDefault();
        var tamount = $('#tamount').val();
        var amount = $('#amount').val();
        var order = $("#order").val();
        var desc = $("#desc").val();
        var sdate = $("#flatpickr03").val();
        var pmeth = $("#bss4").val();


        if(parseFloat(amount)  > parseFloat(tamount)){
          toastr.error("The Maximum amount you can pay is " + tamount)
        }else{
          $.ajax({
            url:'../jquery/sale-history.php',
            type:'post',
            data: {
              order: order, 
              amount: amount,
              desc: desc, 
              sdate: sdate,
              pmeth: pmeth
            },
            success: function(data){
                if(data == 'updated'){
                  toastr.success("Payment Added Successfully")
                 location.reload();
                }else{
                  toastr.error("Something is wrong please try again")
                  console.log(data)
                }
            }
        });
        }
      })

      $(document).on('blur', '#amount', function(){
        var amount = $(this).val()
        var tamount = $('#tamount').val();
        var balance = tamount - amount;
        $('#balance').val(balance)

        if(parseFloat(amount)  > parseFloat(tamount)){
          toastr.error("The Maximum amount you can pay is " + tamount)
          $('#amount').val(tamount)
          var balance = tamount - tamount;
          $('#balance').val(balance)
          
        }

      })

      $("#addPayModal").on('hidden.bs.modal', function(){
        $("#frmAddPay").serialize()
      })




      // Download INvoice 
      $(document).on('click', '#btnDownload', function(){
        var id = $(this).data('id')
        $.ajax({
            url:'../jquery/sale-history.php',
            type:'post',
            data: {
              download: id
            },
            success:function(res){
              console.log(res)
            }
          })
      });

})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Purchase history';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Soo iibin'  ?>
      
      <?php $smenu = 'Liiska iibsasho'  ?>
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
                  <h1 class="page-title"> Purchase history </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>purchase/new" class="btn btn-info text-right"> <i class="fa fa-plus"></i> New Purchase </a>
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
                              <th>Invoice number</th>
                              <th>Purchase date</th>
                              <th>Supplier</th>
                              <th>Purchase status</th>
                              <th>Paid Amount</th>
                              <th>Total Amount</th>
                              <th>Payment status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                              $stmt = "SELECT purchase.purchase_id, purchase.ser, purchase.pur_date, supplier.sup_name, supplier.phone_num, purchase.pur_status, purchase.paid_amount,purchase.gtotal, purchase.balance, purchase.payment_status FROM purchase, supplier WHERE 
                              purchase.supp_id = supplier.supp_id AND purchase.pur_status != 'on-going' ORDER BY purchase.purchase_id DESC";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $id = $row['purchase_id'];
                                $ser = $row['ser'];
                                $date = date('d-m-Y', strtotime($row['pur_date'])) ;
                                $cust = $row['sup_name'];
                                $cphone = $row['phone_num'];
                                $ostatus = $row['pur_status'];
                                $pamount = $row['paid_amount'];
                                $balance = $row['balance'];
                                $tamount = $row['gtotal'];
                                $pstatus = $row['payment_status'];

                                $addPay = "";
                                if($pstatus != "Paid"){
                                  $addPay =  "<button type='button' data-toggle='modal' data-target='#addPayModal' id='btnAddPay' data-id='$id'  class='dropdown-item'> <i class='fa fa-plus'></i> Add payment</button>";
                                }

                                if($pstatus == "Not paid"){ $pstatus = " <span class='badge  badge-danger'>$pstatus</span> "; }
                                else if($pstatus == "Partial payment" ){  $pstatus = " <span class='badge badge-warning'>$pstatus</span> "; }
                                else if($pstatus == "Paid" ){  $pstatus = " <span class='badge  badge-success'>$pstatus</span> "; }
                                
                                if($ostatus == "Recieved"){
                                  $ostatus = "<span class='badge  badge-success'>$ostatus</span>";
                                }else if($ostatus == "Pending"){
                                  $ostatus = "<span class='badge  badge-info'>$ostatus</span>";
                                }else if($ostatus == "Ordered"){
                                  $ostatus = "<span class='badge  badge-warning'>$ostatus</span>";

                                }
                                
                                echo "
                                  <tr>
                                    <td>$ser</td>
                                    <td>$date</td>
                                    <td>$cust</td>
                                    <td> $ostatus </td>
                                    <td> <strong>$pamount $</strong> </td>
                                    <td> <strong>$tamount $</strong> </td>
                                    <td> $pstatus</td>
                                    <td>
                                    <div class='dropdown d-inline-block'>
                                      <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                      <div class='dropdown-menu dropdown-menu-right' style=''>
                                        <div class='dropdown-arrow'></div>
                                        <button type='button' data-toggle='modal' data-target='#orderDetailModal' id='sale_detail' data-id='$id' class='dropdown-item'> <i class='fa fa-eye'></i> View Purchase</button> 
                                        <a href='" . BASE_URL . 'purchase/edit?id='. "$id' type='button' class='dropdown-item'> <i class='fa fa-edit'></i> Edit purchase</a>
                                        $addPay
                                        <button type='button' class='dropdown-item' id='btnDelSale' data-id='$id'><i class='fa fa-trash'></i> Delete purchase</button>
                                        <a  href='" . BASE_URL . 'purchase/download?ref='. "$id' class='dropdown-item btn-info' id='' data-id='$id'><i class='fa fa-download'></i> Download</a>
                                       
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
             


              <!-- Edit customer drawer -->
              <div class="modal modal-drawer fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModal" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg modal-drawer-right" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                          <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
                            <!-- .modal-header -->
                            <div class="modal-header">
                            
                                <div class="col-md-6">
                                  <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Purchase detail</strong> </h5>
                                </div>
                                <div class="col-md-6 text-right">
                                  <button class="btn btn-success" type='button' id='btnDownload' data-id=''> <i class="fa fa-download"></i> Download </button>
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
                        <div class="modal-dialog" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                          <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalLongLabel" class="modal-title"> Add Payment </h5>
                            </div><!-- /.modal-header -->

                            <form  method="post" class="" id='frmAddPay'>
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="form-group col-md-12">
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

                                <div class="form-group col-md-12">
                                  <label for="">Date *</label>
                                  <input id="flatpickr03" type="hidden" placeholder="Select sale date" name='sdate' id='sdate' class="form-control flatpickr-input rounded-0 sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                                  
                                </div>

                                <div class="form-group col-md-12">
                                  <label for="">Amount *</label>
                                  <input type="hidden" name="tamount" id='tamount'>
                                  <input type="number" autocomplete='off'  min='0' name="amount" id="amount" required step='0.01' class="form-control" placeholder='0.00'>
                                  <p class='text-primary' id='maxAmount'>The maximum amount you can pay is <strong>0.00 $</strong></p>
                                </div>

                                <div class="form-group col-md-12">

                                  <label for="">Balance</label>
                                  <input type="hidden" name="order" id='order'>

                                  <input type="number" name="balance" id="balance" readonly step='0.01' class="form-control" placeholder='0.00'>
                                </div>

                                <div class="form-group col-md-12">
                                  <label for="">Description</label>
                                  <textarea name="desc" id="desc" placeholder='Description goes here...' class='form-control'></textarea>
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
                  url:'../jquery/purchase-history.php',
                  type:'post',
                  data: 'btnDelSale='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Purchase deleted successfully.',
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
        // Set the download button data-id
        $('#btnDownload').attr('data-id', id)
        $.ajax({
            url:'../jquery/purchase-history.php',
            type:'post',
            data: 'viewSingleSale='+id,
            success: function(data){
                $('#saleDetailP').html(data)
                console.log(data)
                

            }
        });
      })

      // Edit customer - displaying information
      $(document).on('click', '#edit_acc', function(){
        var id = $(this).data('id')
        //$('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/purchase-history.php',
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
        $('.overlay').removeClass('d-none')
        $.ajax({
            url:'../jquery/purchase-history.php',
            type:'post',
            data: 'addPayAmount='+id,
            success: function(data){
                $('#maxAmount').html('The maximum amount you can pay is <strong> ' + data + '</strong>')
                //$('.overlay').addClass('d-none');
                $('.overlay').addClass('d-none')

              $('#tamount').val(data)
              console.log(data)
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

        $('.overlay').removeClass('d-none')

    
          if( parseFloat(amount)  > parseFloat(tamount)){
            toastr.error("The Maximum amount you can pay is " + tamount)
            $('.overlay').addClass('d-none')
          }else{
            $.ajax({
              url:'../jquery/purchase-history.php',
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
                    location.reload()
                  }else{
                    toastr.error("Something is wrong please try again")
                    console.log(data)
                    $('.overlay').addClass('d-none')
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
          toastr.error("The Maximum amount you can pay is " + tamount + " $")
         // $('#amount').val(tamount)
          var balance = tamount - tamount;
          $('#balance').val(balance)
          
        }
      })

      $("#addPayModal").on('hidden.bs.modal', function(){
        $("#frmAddPay").serialize()
      })

      // Download Purchase Invoice
      $(document).on('click', '#btnDownload', function(){
        var id = $(this).data('id')
        if(id){
          // Redirect to purchase download page
          window.open('<?= BASE_URL ?>purchase/download?ref=' + id, '_blank');
        } else {
          toastr.error('No purchase ID found');
        }
      });




})

</script>
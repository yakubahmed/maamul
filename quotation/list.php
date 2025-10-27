<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Quotation list';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Qiimayn'  ?>
      
      <?php $smenu = 'Liiska qiimeynta'  ?>
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
                  <h1 class="page-title"> Quotation list </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>quotation/add" class="btn btn-info text-right"> <i class="fa fa-plus"></i> New Quotation </a>
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
                              <th>Qoutation date</th>
                              <th>Customer</th>
                              <th>Total Amount</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                              $stmt = "SELECT quotation.qoutation_id, quotation.grand_total, quotation.ser, quotation.date, customer.cust_name, customer.cust_phone, quotation.total FROM quotation, customer WHERE 
                              quotation.cust_id = customer.customer_id AND quotation.status != 'on-going' ORDER BY quotation.qoutation_id DESC";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $id = $row['qoutation_id'];
                                $ser = $row['ser'];
                                $date = date('F d, Y', strtotime($row['date'])) ;
                                $cust = $row['cust_name'];
                                $cphone = $row['cust_phone'];
                                
                                $tamount = $row['grand_total'];

                                
                                echo "
                                  <tr>
                                    <td>$ser</td>
                                    <td>$date</td>
                                    <td>$cust</td>
                                    <td> <strong>$tamount $</strong> </td>
                                    <td>
                                    <div class='dropdown d-inline-block'>
                                      <button class='btn btn-icon btn-secondary' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-fw fa-ellipsis-h'></i></button>
                                      <div class='dropdown-menu dropdown-menu-right' style=''>
                                        <div class='dropdown-arrow'></div>
                                        <button type='button' data-toggle='modal' data-target='#orderDetailModal' id='sale_detail' data-id='$id' class='dropdown-item'> <i class='fa fa-eye'></i> View Quotation</button> 
                                        <button type='button' data-toggle='modal' data-target='#transferModal' id='btnTransfer' data-id='$id' class='dropdown-item'> <i class='fa fa-shopping-bag'></i> Convert to sale</button> 
                                        <a href='" . BASE_URL . 'quotation/edit?id='. "$id' type='button' class='dropdown-item'> <i class='fa fa-edit'></i> Edit Quotation</a>
                                      
                                        <button type='button' class='dropdown-item' id='btnDelSale' data-id='$id'><i class='fa fa-trash'></i> Delete quotation</button>
                                        <a   href='" . BASE_URL . 'quotation/download?ref='. "$id' class='dropdown-item ' id='btnDownload' data-id='$id'><i class='fa fa-download'></i> Download </a>
                                       
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

                            <!-- .modal-header -->
                            <div class="modal-header">
                            
                                <div class="col-md-6">
                                  <h5 id="exampleModalDrawerRightLabel" class="modal-title"> <strong>Sale detail</strong> </h5>
                                </div>
                                <div class="col-md-6 text-right">
                                  <button class="btn btn-success" type='button'> <i class="fa fa-download"></i> Download </button>
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
                    <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongLabel" aria-hidden="true">
                        <!-- .modal-dialog -->
                        <div class="modal-dialog modal-lg" role="document">
                          <!-- .modal-content -->
                          <div class="modal-content">
                            

                            <!-- .modal-header -->
                            <div class="modal-header">
                              <h5 id="exampleModalLongLabel" class="modal-title"> Convert to sale </h5>
                            </div><!-- /.modal-header -->

                            <form  method="post" class="" id='frmTransfer'>
                              <!-- .modal-body -->
                              <div class="modal-body">
                                <div class="container-fluid">
                                  <div class="row" id='saleDetail'>

                                   

                                  </div>
                                </div>

                                                           
                              </div><!-- /.modal-body -->
                              <!-- .modal-footer -->
                              <div class="modal-footer ">
                               
                                <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button> 
                                <button type="submit" class="btn btn-info rounded-0">Convert to sale</button>
                                </div>
                                
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
        console.log('Deleting quotation ID:', id); // Debug log
        
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
                  url:'../jquery/qoutation-list.php',
                  type:'post',
                  data: 'btnDelSale='+id,
                  success: function(data){
                    data = data.trim(); // Trim whitespace
                    console.log('Delete response:', data); // Debug log
                    
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Quotation deleted successfully.',
                        'success'
                      ).then(() => {
                        location.reload();
                      })
                    }else if(data == 'invalid_input'){
                      Swal.fire(
                        'Error!',
                        'Invalid quotation ID.',
                        'error'
                      )
                    }else{
                      Swal.fire(
                        'Error!',
                        'Failed to delete quotation: ' + data,
                        'error'
                      )
                      console.log('Error details:', data)
                    }
                  },
                  error: function(xhr, status, error){
                    console.log('AJAX Error:', error);
                    Swal.fire(
                      'Error!',
                      'Network error. Please try again.',
                      'error'
                    )
                  }
                })

              }
            })
      })




      // Displaying sale detail
      $(document).on('click', '#sale_detail', function(){
        var id = $(this).data('id')
        $.ajax({
            url:'../jquery/qoutation-list.php',
            type:'post',
            data: 'viewSingleQuote='+id,
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

        $.ajax({
            url:'../jquery/purchase-history.php',
            type:'post',
            data: 'addPayAmount='+id,
            success: function(data){
                $('#maxAmount').html('The maximum amount you can pay is <strong> ' + data + '</strong>')
                //$('.overlay').addClass('d-none');

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

    
          if( parseFloat(amount)  > parseFloat(tamount)){
            toastr.error("The Maximum amount you can pay is " + tamount)
          }else if (parseFloat(amount)  <= parseFloat(tamount)){
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
          $('#amount').val(tamount)
          
        }
      })


    $(document).on('click', '#btnTransfer', function(){
      var id = $(this).data('id')

      $.ajax({
              url:'../jquery/qoutation-list.php',
              type:'post',
              data: { quotation_id: id, },
              success: function(data){
                 $('#saleDetail').html(data)
              }
      });

    })

    //Validation remove is-invalid
    $(document).on('change', '.ddate', function(){
      $('.ddate').removeClass('is-invalid')

    })

    $(document).on('change', '.sdate', function(){
      $('.sdate').removeClass('is-invalid')

    })

    $('#frmTransfer').on('submit', function(e){
      e.preventDefault()
      console.log('Convert to sale clicked'); // Debug log
      
      var id = $("#qoutation_id").val()
      var status = $('#status').val()
      var ddate = $('.ddate').val()
      var sdate = $('.sdate').val()
      var pno = $('#pno').val()
      var paid_amount = $('#amount').val()
      var total_amount = $('#tamount').val()
      var balance = $('#balance').val()
      var pmeth = $('.pmeth').val()

      if(sdate == ""){
        toastr.error("Please select sale date")
        $('.sdate').addClass('is-invalid')
        return false;
      }else if (ddate == ""){
        toastr.error("Please select due date")
        $('.ddate').addClass('is-invalid')
        return false;
      }

      if(parseFloat(paid_amount)  > parseFloat(total_amount)){
          toastr.error("The Maximum amount you can pay is " + total_amount + " $")
          var balance = total_amount - total_amount;
          $('#balance').val(balance)
          return false;
      }
      
      console.log('Converting quotation ID:', id); // Debug log
      
      $.ajax({
              url:'../jquery/qoutation-list.php',
              type:'post',
              data: { 
                transfer_quot: id,
                status: status, 
                duedate: ddate, 
                saledate: sdate, 
                pno: pno,
                paid_amount: paid_amount, 
                total_amount: total_amount, 
                balance: balance, 
                account: pmeth
               },
              beforeSend: function(){
                $('.overlay').removeClass('d-none');
              },
              success: function(res){
                 res = res.trim();
                 console.log('Convert response:', res); // Debug log
                 $('.overlay').addClass('d-none');
                 
                 if(res == 'success'){
                  toastr.success("Quotation converted to sale successfully")
                  setTimeout(function(){
                    window.location.replace('<?= BASE_URL ?>sales/history');
                  }, 1000);
                 }else if(res == 'session_expired'){
                  toastr.error('Your session has expired. Please login again.');
                  setTimeout(function(){
                    window.location.replace('<?= BASE_URL ?>login');
                  }, 2000);
                 }else if(res == 'missing_fields'){
                  toastr.error('Please fill in all required fields');
                 }else if(res == 'quotation_not_found'){
                  toastr.error('Quotation not found');
                 }else if(res == 'no_items'){
                  toastr.error('This quotation has no items');
                 }else{
                  console.log('Error details:', res); // Debug log
                  toastr.error("Failed to convert quotation: " + res)
                 }
              },
              error: function(xhr, status, error){
                console.log('AJAX Error:', error); // Debug log
                $('.overlay').addClass('d-none');
                toastr.error("Network error. Please try again.");
              }
      });

    });

})

</script>
<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = 'Sale history';  include(ROOT_PATH . '/inc/header.php'); ?>
      <?php $menu = 'Sale'  ?>
      
      <?php $smenu = 'Sales history'  ?>
  <body>
    <!-- .app -->
    <div class="app">
      <!-- [if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif] -->
      <!-- .app-header -->
      <?php include(ROOT_PATH .'/inc/nav.php'); ?>
      <!-- .app-aside -->
      <?php //$isactive = "Sale" ?>
      <?php //$isactive2 = "Sales history" ?>
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
                  <h1 class="page-title"> Sale history </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>sales/multiple-payment" class="btn btn-success"> <i class="fa fa-money-bill-wave"></i> Multiple Payment </a>
                    <a href="<?= BASE_URL ?>sales/new" class="btn btn-info"> <i class="fa fa-user-plus"></i> New Sale </a>
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
                              <th>Invoice No</th>
                              <th>Sale date</th>
                              <th>Customer</th>
                              <th>Sale status</th>
                              <th>Paid Amount</th>
                              <th>Total Amount</th>
                              <th>Payment status</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                              $stmt = "SELECT orders.order_id, orders.ser, orders.order_date, customer.cust_name, 
                              customer.cust_phone, orders.order_status, orders.amount,orders.pr_af_dis, orders.balance, orders.payment_status 
                              FROM orders, customer WHERE orders.cust_id = customer.customer_id AND orders.order_status != 'on-going' ORDER BY orders.order_id DESC";
                              $result = mysqli_query($con, $stmt);
                              while($row = mysqli_fetch_assoc($result)){
                                $id = $row['order_id'];
                                $ser = $row['ser'];
                                $date = date('d-m-Y', strtotime($row['order_date'])) ;
                                $cust = $row['cust_name'];
                                $cphone = $row['cust_phone'];
                                $ostatus = $row['order_status'];
                                $pamount = $row['amount'];
                                $balance = $row['balance'];
                                $tamount = $row['pr_af_dis'];
                                $pstatus = $row['payment_status'];

                                $addPay = "";
                                if($pstatus != "Paid"){
                                  $addPay =  "<button type='button' data-toggle='modal' data-target='#addPayModal' id='btnAddPay' data-id='$id'  class='dropdown-item'> <i class='fa fa-plus'></i> Add payment</button>";
                                }

                                if($pstatus == "Not paid"){ $pstatus = " <span class='badge  badge-danger'>$pstatus</span> "; }
                                else if($pstatus == "Partial payment" ){  $pstatus = " <span class='badge badge-warning'>$pstatus</span> "; }
                                else if($pstatus == "Paid" ){  $pstatus = " <span class='badge  badge-success'>$pstatus</span> "; }
                                
                                if($ostatus == "Confirmed"){
                                  $ostatus = "<span class='badge  badge-success'>$ostatus</span>";
                                }else if($ostatus == "Delivered"){
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
                                        <button type='button' data-toggle='modal' data-target='#orderDetailModal' id='sale_detail' data-id='$id' class='dropdown-item'> <i class='fa fa-eye'></i> View Sale</button> 
                                        <a href='" . BASE_URL . 'sales/edit?id='. "$id' type='button' class='dropdown-item'> <i class='fa fa-edit'></i> Edit sale</a>
                                        $addPay
                                        <button type='button' class='dropdown-item btn-success' id='btnSendWhatsApp' data-id='$id' data-phone='$cphone' data-invoice='$ser'><i class='fab fa-whatsapp'></i> Send to WhatsApp</button>
                                        <a  href='" . BASE_URL . 'sales/download?ref='. "$id' class='dropdown-item btn-info' id='' data-id='$id'><i class='fa fa-download'></i> Download</a>
                                        <button type='button' class='dropdown-item btn-danger' id='btnDelSale' data-id='$id'><i class='fa fa-trash'></i> Delete sale</button>
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
                                  <button class="btn btn-success" type='button' id='btnDownload' data-id=''> <i class="fa fa-download"></i> Download </button>
                                </div>
                          
                            </div><!-- /.modal-header -->
                            <!-- .modal-body -->
                            <div class="modal-body" >
                            <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
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
                              <div class="overlay d-none">
                          <i class="fas fa-2x fa-sync fa-spin text-light"></i>
                      </div>
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
                  url:'../jquery/sale-history.php',
                  type:'post',
                  data: 'btnDelSale='+id,
                  success: function(data){
                    if(data == 'deleted'){
                      Swal.fire(
                        'Deleted!',
                        'Invoice deleted successfully.',
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
            url:'../jquery/sale-history.php',
            type:'post',
            data: 'viewSingleSale='+id,
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
        $('.overlay').removeClass('d-none')

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
          toastr.error("The Maximum amount you can pay is " + tamount)
          $('#amount').val(tamount)
          var balance = tamount - tamount;
          $('#balance').val(balance)
          
        }

      })

      $("#addPayModal").on('hidden.bs.modal', function(){
        $("#frmAddPay").serialize()
      })




      // Download Invoice 
      $(document).on('click', '#btnDownload', function(){
        var id = $(this).data('id')
        if(id){
          // Redirect to sales download page
          window.open('<?= BASE_URL ?>sales/download?ref=' + id, '_blank');
        } else {
          toastr.error('No invoice ID found');
        }
      });

      // Send Invoice to WhatsApp
      $(document).on('click', '#btnSendWhatsApp', function(e){
        e.preventDefault();
        
        var orderId = $(this).data('id');
        var phone = $(this).data('phone');
        var invoiceNo = $(this).data('invoice');
        
        console.log('WhatsApp clicked - Order ID:', orderId, 'Phone:', phone, 'Invoice:', invoiceNo);
        
        if(!phone || phone == ''){
          toastr.error('Customer phone number not found');
          return;
        }
        
        // Clean phone number (remove spaces, dashes, etc.)
        phone = phone.toString().replace(/[\s\-\(\)]/g, '');
        
        // Ensure phone number starts with country code
        if(!phone.startsWith('+')){
          // If number starts with 0, replace with +252 (Somalia)
          if(phone.startsWith('0')){
            phone = '252' + phone.substring(1);
          } else if(!phone.startsWith('252')){
            // If no country code, add 252
            phone = '252' + phone;
          }
        } else {
          // Remove + sign for WhatsApp API
          phone = phone.substring(1);
        }
        
        // Show loading message
        Swal.fire({
          title: 'Preparing Invoice',
          html: 'Downloading invoice PDF...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        
        // First, download the PDF
        var downloadUrl = '<?= BASE_URL ?>sales/download?ref=' + orderId;
        
        // Create a temporary iframe to download the PDF
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);
        
        // Wait for download to start, then open WhatsApp
        setTimeout(function(){
          // Remove iframe
          document.body.removeChild(iframe);
          
          // Create WhatsApp message
          var message = 'Hello! 👋\n\n';
          message += 'Invoice: *' + invoiceNo + '*\n\n';
          message += 'Please find attached invoice PDF.\n\n';
          message += 'Thank you for your business!\n\n';
          message += '*RAMAAS Electronic & Cosmetics Center*';
          
          // Encode message for URL
          var encodedMessage = encodeURIComponent(message);
          
          // Create WhatsApp URL
          var whatsappUrl = 'https://wa.me/' + phone + '?text=' + encodedMessage;
          
          console.log('WhatsApp URL:', whatsappUrl);
          
          // Close loading and show instructions
          Swal.fire({
            title: 'Send via WhatsApp',
            html: '<div style="text-align: left;">' +
                  '<p><strong>Steps to send invoice:</strong></p>' +
                  '<ol>' +
                  '<li>PDF invoice has been downloaded ✓</li>' +
                  '<li>WhatsApp chat will open</li>' +
                  '<li>Click the <strong>📎 attachment</strong> icon</li>' +
                  '<li>Select <strong>Document</strong></li>' +
                  '<li>Choose the downloaded invoice PDF</li>' +
                  '<li>Click <strong>Send</strong></li>' +
                  '</ol>' +
                  '</div>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#25D366',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fab fa-whatsapp"></i> Open WhatsApp',
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              // Open WhatsApp
              window.open(whatsappUrl, '_blank');
              toastr.success('WhatsApp opened! Attach the downloaded PDF.');
            }
          });
        }, 2000); // Wait 2 seconds for download to start
      });

})

</script>
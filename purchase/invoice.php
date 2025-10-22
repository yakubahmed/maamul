<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add customer"; include(ROOT_PATH . '/inc/header.php'); ?>
  <body >
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
                  <h1 class="page-title"> INVOICE </h1>
                </div>
                <div class="col-md-6 text-right">
                    <button  class="btn btn-info text-right" id='print'> <i class="fa fa-print"></i> Print </button>
                </div>
              </div>
                
              </header><!-- /.page-title-bar -->
              <!-- .page-section -->
              <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">

                  <div class="row">
                      <div class="col-md-12">
                          <div class="card print">
                              <div class="card-body">
                                  
                                  <div class="row align-items-center ">
                                    <div class="col-md-6 text-center">
                                        <img src="<?= BASE_URL ?>assets/images/ramaas_logo.jpg" height="100" alt="">
                                        
                                      </div>
                                    <div class="col-md-6 text-center">
                                        <h5>SUBMALCO</h5>
                                        <p>Hantiwadaag, 252 Jowhar, Somalia <br>
                                        Tell: 252 61 6246740 Email: info@submalco.so</p>
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                    </div>

                                    <div class="col-md-6">
                                        <h6> <strong>BILL TO:</strong> </h6>
                                        <p>Yakub Ahmed <br>
                                        Mogadishu, Benadir, Somalia <br>
                                        Tell: +252 616246740 <br>
                                        Email: yaaqa91@gmail.com</p>
                                    </div>

                                    <div class="col-md-6">
                                       
                                   
                                          <h5>INVOICE</h5>
                                          
                                          <p>Invoice: <strong>SL02</strong><br>
                                          Date: <strong>June 10, 2022</strong> <br>
                                          Sale Status: <strong>Confirmed</strong> <br>
                                          Payment status: <strong>Paid</strong> </p>
                                                    
                                             
                                            
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Iphone XS Max 512 GB</td>
                                                    <td>1,500 $</td>
                                                    <td>10 PC</td>
                                                    <td>9,000 $</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Fired Chicken & Chips</td>
                                                    <td>2 $</td>
                                                    <td>10 PC</td>
                                                    <td>8.00 $</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Iphone XS Max 512 GB</td>
                                                    <td>1,500 $</td>
                                                    <td>10 PC</td>
                                                    <td>9,000 $</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan='4' class='text-right'> 
                                                        Sub Total (USD)
                                                    </td>
                                                    <td> <h6> 5,500 $ </h6> </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='4' class='text-right'> 
                                                        Order Discount (USD)
                                                    </td>
                                                    <td> <h6>0.00 $ </h6> </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='4' class='text-right'> 
                                                        Grand Total (USD)
                                                    </td>
                                                    <td> <h6> 5,500 $ </h6> </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='4' class='text-right'> 
                                                        Paid Amount (USD)
                                                    </td>
                                                    <td> <h6> 5,500 $ </h6> </td>
                                                </tr>

                                                <tr>
                                                    <td colspan='4' class='text-right'> 
                                                        Balance (USD)
                                                    </td>
                                                    <td> <h6> 0.00 $ </h6> </td>
                                                </tr>
                                                
                                            </tfoot>
                                        </table>
                                        <div class="col-md-6">
                                            <br>
                                            <hr>
                                            <p>Stamp & Signature</p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-success text-center">
                                                &copy <?= date('Y') ?> <strong>SUBMALCO.</strong>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
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

  $('#frmCust').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/customer.php', 
      type:'post', 
      data: $('#frmCust').serialize(),
      success: function(res){
        if(res == 'found_email'){
          toastr.error("This email account is allready with another customer, try another one")
          $('.overlay').addClass('d-none')
          $('#cemail').addClass('is-invalid')
        }else if (res == "phone_found"){
          $('.overlay').addClass('d-none')
          toastr.error("This phone number is allready registered with another customer, try another one ")
          $('#cphone').addClass('is-invalid')
        }else if (res == 'success'){
          toastr.success("Customer registered successfully")
          $('#frmCust')[0].reset();
          $('.overlay').addClass('d-none')
          $('#cphone').removeClass('is-invalid')
          $('#cemail').removeClass('is-invalid')
        }
      }
    })
  })



  function PrintElem(elem) {
    Popup(jQuery(elem).html());
}

function Popup(data) {
    var mywindow = window.open('', 'Sales Invoice', 'height=1748px,width=1240px');
    mywindow.document.write('<html><head><title>Sales Invoice</title> ');
    mywindow.document.write('<link rel="stylesheet" href="<?= BASE_URL ?>/assets/stylesheets/theme.min.css" data-skin="default">');   
    mywindow.document.write('<link rel="stylesheet" href="<?= BASE_URL ?>/assets/stylesheets/custom.css">');  
    mywindow.document.write('<link rel="stylesheet" href="<?= BASE_URL ?>/assets/vendor/bootstrap/js/bootstrap.min.js">');  
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
    setTimeout(() => {
      mywindow.document.close();
      mywindow.print()
    }, 2000);
   // ;                        
}
     
$(document).on('click', '#print', function(){
  PrintElem('.print')
})


})

</script>
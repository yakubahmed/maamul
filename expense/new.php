<?php include('../path.php'); ?>

<style>
 
</style>

<?php $title = "Add Expense"; include(ROOT_PATH . '/inc/header.php'); ?>

      <?php $menu = 'Expense'  ?>
      
      <?php $smenu = 'New expense'  ?>
      
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
                  <h1 class="page-title"> Add expense </h1>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= BASE_URL ?>expense/list" class="btn btn-info text-right"> <i class="fa fa-list"></i> View expenses </a>
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
                        
                        <form class='row' method='post' id='frmAddExp'>

                          <div class='form-group col-md-4'>
                            <label for=''> Expense type *</label>
                            <select name="etype" id="etype" class="form-control" required>
                                <option value="">Select expense type</option>
                                <?php 
                                    $stmt = "SELECT * FROM expense_type";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $id  = $row['expense_type_id'];
                                        $name  = $row['name'];
                                        echo "
                                            <option value='$id'>$name</option>
                                        ";
                                    }
                                
                                ?>
                            </select>
                          </div>

                          <div class='form-group col-md-4'>
                            <label for=''> Expense For *</label>
                            <input type="text" name="name" id="name" class="form-control" required placeholder='Enter expense for' autocomplete='off'>
                          </div>
                          <div class='form-group col-md-4'>
                            <label for=''> Date *</label>
                            <input id="flatpickr03" type="hidden" required placeholder="Select date" name='date' id='date' class="form-control flatpickr-input  sdate" data-toggle="flatpickr" required data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d">
                    
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Amount</label>
                            <div class="input-group rounded-0">
                              <label class="input-group-prepend" for="pi9"><span class="badge">$</span></label> <input type="number" name='amount' id='amount'  autocomplete='off' placeholder='Enter amount' step="0.01" required class="form-control rounded-0" id="pi9">
                            </div>
                          </div>

                          <div class='form-group col-md-6'>
                            <label for=''> Account *</label>
                            <select name="account" id="account" class="form-control" required>
                                <option value="">Select expense type</option>
                                <?php 
                                    $stmt = "SELECT * FROM account";
                                    $result = mysqli_query($con, $stmt);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $id  = $row['account_id'];
                                        $name  = $row['account_name'];
                                        $num  = $row['account_number'];
                                        echo "
                                            <option value='$id'>$name - $num</option>
                                        ";
                                    }
                                
                                ?>
                            </select>
                          </div>

                          <div class='form-group col-md-12'>
                            <label for=''> Description</label>
                            <textarea name='desc' id='desc' class='form-control ' required></textarea>
                          </div>

                          <div class='form-group col-md-6'>
                            <button type='submit' class='btn btn-info rounded-0'>Save expense</button>
                          </div>
                        </form>
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

  $('#frmAddExp').on('submit', function(e){
    e.preventDefault();
    $('.overlay').removeClass('d-none')
    var id = $('#cust_id').val();
    console.log(id)
    $.ajax({
      url:'../jquery/expense.php', 
      type:'post', 
      dataType: 'json',
      data: $('#frmAddExp').serialize(),
      success: function(res){
       if (res.success === true){
          toastr.success("Expense Added successfully")
          $('#frmAddExp')[0].reset();
          $('.overlay').addClass('d-none')
        setTimeout(() => {
            window.location.replace("<?= BASE_URL ?>expense/list")
            
        }, 500);
        }else if (res.error === 'not_logged_in'){
          toastr.error("Session expired. Please login again.")
          setTimeout(function(){
            window.location.href = '<?= BASE_URL ?>login.php';
          }, 2000);
        }else if (res.error === 'invalid_input'){
          toastr.error("Please fill in all required fields with valid data")
          $('.overlay').addClass('d-none')
        }else{
            toastr.error("Something is wrong please try again")
            $('.overlay').addClass('d-none')
            console.log(res)
        }
      },
      error: function(xhr, status, error){
        console.log('AJAX Error:', error);
        console.log('Response:', xhr.responseText);
        toastr.error("Network error. Please try again.");
        $('.overlay').addClass('d-none');
      }
    })
  })



})

</script>
<?php include('../path.php'); ?>
<?php $title = "New Money Loan"; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Lacag deen' ?>
<?php $smenu = 'Deen cusub' ?>

<body>
  <div class="app">
    <?php include(ROOT_PATH .'/inc/nav.php'); ?>
    <?php include(ROOT_PATH . '/inc/sidebar.php'); ?>

    <main class="app-main">
      <div class="wrapper">
        <div class="page">
          <div class="page-inner">
            <header class="page-title-bar">
              <div class="row">
                <div class="col-md-6">
                  <h1 class="page-title"> Money Loan</h1>
                </div>
                <div class="col-md-6 text-right">
                  <a href="<?= BASE_URL ?>loans/list" class="btn btn-secondary">
                    <i class="fa fa-list"></i> Loans List
                  </a>
                </div>
              </div>
            </header>

            <div class="page-section">
              <div class="card">
                <div class="card-body">
                  <form class="row" id="frmLoan">
                    <div class="form-group col-md-6">
                      <label>Customer *</label>
                      <select name="customer" id="customer" class="form-control" data-toggle='selectpicker' required data-live-search='true'>
                        <option value=''>Select customer</option>
                        <?php 
                          $stmt = "SELECT * FROM customer WHERE customer_id != 29 ORDER BY cust_name ASC";
                          $result = mysqli_query($con, $stmt);
                          while($row = mysqli_fetch_assoc($result)){
                            echo "<option value='{$row['customer_id']}'>{$row['cust_name']} - {$row['cust_phone']}</option>";
                          }
                        ?>
                      </select>
                    </div>

                    <div class="form-group col-md-3">
                      <label>Loan Date *</label>
                      <input type="text" name="loan_date" id="loan_date" class="form-control" data-toggle="flatpickr" data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group col-md-3">
                      <label>Amount *</label>
                      <input type="number" step="0.01" name="amount" id="amount" class="form-control" required placeholder="0.00">
                    </div>

                    <div class="form-group col-md-6">
                      <label>Funding Account (optional)</label>
                      <select name="account" id="account" class="form-control" data-toggle='selectpicker' data-live-search='true'>
                        <option value="">Select account</option>
                        <?php 
                          $stmt = "SELECT * FROM account ORDER BY account_name ASC";
                          $result = mysqli_query($con, $stmt);
                          while($row = mysqli_fetch_assoc($result)){
                            echo "<option value='{$row['account_id']}'>{$row['account_name']} - {$row['account_number']}</option>";
                          }
                        ?>
                      </select>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Note</label>
                      <input type="text" name="note" id="note" class="form-control" placeholder="Optional note">
                    </div>

                    <div class="form-group col-md-12 text-center">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Loan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include(ROOT_PATH .'/inc/footer.php'); ?>
    </main>
  </div>

<?php include(ROOT_PATH .'/inc/footer_links.php'); ?>
<script>
$(function(){
  if($('.selectpicker').length){ $('.selectpicker').selectpicker(); }

  $('#frmLoan').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      url: '../jquery/loans/loan.php',
      type: 'POST',
      data: $(this).serialize() + '&action=create',
      success: function(res){
        res = (res||'').trim();
        if(res === 'success'){
          toastr.success('Loan saved');
          setTimeout(function(){ window.location.href = '<?= BASE_URL ?>loans/list'; }, 800);
        }else{
          toastr.error('Failed to save loan');
          console.log(res);
        }
      },
      error: function(){ toastr.error('Server error'); }
    })
  })
});
</script>

</body>
</html>


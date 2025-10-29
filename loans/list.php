<?php include('../path.php'); ?>
<?php $title = "Money Loans"; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Lacag deen' ?>
<?php $smenu = 'Liiska deenta' ?>

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
                  <h1 class="page-title"> Money Loans</h1>
                </div>
                <div class="col-md-6 text-right">
                  <a href="<?= BASE_URL ?>loans/new" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Money Loan
                  </a>
                </div>
              </div>
            </header>

            <div class="page-section">
              <div class="card">
                <div class="card-body">
                  <div id="list_place">
                    <p class="text-center text-muted">Loading...</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="frmPayment">
                      <input type="hidden" name="loan_id" id="loan_id">
                      <div class="form-group">
                        <label>Amount *</label>
                        <input type="number" step="0.01" name="amount" id="pay_amount" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Date *</label>
                        <input type="text" name="date" id="pay_date" class="form-control" data-toggle="flatpickr" data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d" value="<?= date('Y-m-d') ?>">
                      </div>
                      <div class="form-group">
                        <label>Account</label>
                        <select name="account" id="pay_account" class="form-control" data-toggle='selectpicker' data-live-search='true'>
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
                      <div class="form-group">
                        <label>Note</label>
                        <input type="text" name="note" id="pay_note" class="form-control" placeholder="Optional">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnSavePayment">Save Payment</button>
                  </div>
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
  loadList();
  if($('.selectpicker').length){ $('.selectpicker').selectpicker(); }

  function loadList(){
    $.ajax({
      url: '../jquery/loans/loan.php',
      type: 'POST',
      data: {action: 'list'},
      success: function(html){ $('#list_place').html(html); },
      error: function(){ $('#list_place').html('<div class="alert alert-danger">Failed to load</div>'); }
    })
  }

  $(document).on('click', '.btn-add-payment', function(){
    var id = $(this).data('id');
    $('#loan_id').val(id);
    $('#paymentModal').modal('show');
  })

  $('#btnSavePayment').on('click', function(){
    $.ajax({
      url: '../jquery/loans/loan.php',
      type: 'POST',
      data: $('#frmPayment').serialize() + '&action=add_payment',
      success: function(res){
        res = (res||'').trim();
        if(res === 'success'){
          toastr.success('Payment recorded');
          $('#paymentModal').modal('hide');
          loadList();
        }else{
          toastr.error('Failed to record payment');
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


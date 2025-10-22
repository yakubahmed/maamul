<?php include('../path.php'); ?>

<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance:textfield;
}

.invoice-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.invoice-card.selected {
    border-left-color: #00a28a;
    background-color: #f0f9ff;
}

.payment-summary {
    position: sticky;
    top: 20px;
}
</style>

<?php $title = "Multiple Invoice Payment"; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Iibin' ?>
<?php $smenu = 'Multiple Payment' ?>

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
                                    <h1 class="page-title">
                                        <i class="fas fa-money-bill-wave"></i> Multiple Invoice Payment
                                    </h1>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= BASE_URL ?>sales/history" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Back to Sales
                                    </a>
                                </div>
                            </div>
                        </header>

                        <div class="page-section">
                            <div class="row">
                                <!-- Customer Selection & Invoice List -->
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Select Customer & Invoices</h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Customer Selection -->
                                            <div class="form-group">
                                                <label for="customer">Customer *</label>
                                                <select name="customer" id="customer" class="form-control" data-toggle='selectpicker' required data-live-search='true'>
                                                    <option value=''>Select customer</option>
                                                    <?php
                                                    $stmt = "SELECT DISTINCT c.customer_id, c.cust_name, c.cust_phone 
                                                             FROM customer c 
                                                             INNER JOIN orders o ON c.customer_id = o.cust_id 
                                                             WHERE o.balance > 0 
                                                             ORDER BY c.cust_name ASC";
                                                    $result = mysqli_query($con, $stmt);
                                                    while($row = mysqli_fetch_assoc($result)){
                                                        echo "<option value='{$row['customer_id']}'>{$row['cust_name']} - {$row['cust_phone']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Outstanding Invoices -->
                                            <div id="invoice-list" class="d-none">
                                                <hr>
                                                <h5 class="mb-3">Outstanding Invoices</h5>
                                                <div id="invoices-container">
                                                    <!-- Invoices will be loaded here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary & Payment Info -->
                                <div class="col-md-4">
                                    <div class="payment-summary">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h5 class="mb-0">Payment Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th>Selected Invoices:</th>
                                                        <td class="text-right"><span id="selected-count">0</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Due:</th>
                                                        <td class="text-right">$<span id="total-due">0.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Paying:</th>
                                                        <td class="text-right text-success"><strong>$<span id="total-paying">0.00</span></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Remaining Balance:</th>
                                                        <td class="text-right text-danger">$<span id="remaining-balance">0.00</span></td>
                                                    </tr>
                                                </table>

                                                <hr>

                                                <div class="form-group">
                                                    <label for="payment-method">Payment Method *</label>
                                                    <select name="payment-method" id="payment-method" class="form-control">
                                                        <option value="">Select payment method</option>
                                                        <?php 
                                                        $stmt = "SELECT * FROM account";
                                                        $result = mysqli_query($con, $stmt);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo "<option value='{$row['account_id']}'>{$row['account_name']} - {$row['account_number']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment-date">Payment Date *</label>
                                                    <input type="text" id="payment-date" class="form-control" data-toggle="flatpickr" data-alt-input="true" data-alt-format="F j, Y" data-date-format="Y-m-d" value="<?= date('Y-m-d') ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="payment-note">Note (Optional)</label>
                                                    <textarea id="payment-note" class="form-control" rows="3" placeholder="Add payment note..."></textarea>
                                                </div>

                                                <button type="button" class="btn btn-success btn-block btn-lg" id="btn-process-payment" disabled>
                                                    <i class="fas fa-check-circle"></i> Process Payment
                                                </button>
                                            </div>
                                        </div>
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
$(document).ready(function(){
    let selectedInvoices = {};

    // Load customer invoices
    $('#customer').on('change', function(){
        const customerId = $(this).val();
        
        if(customerId){
            $.ajax({
                url: '../jquery/sales/multiple-payment.php',
                type: 'POST',
                data: {get_customer_invoices: true, customer_id: customerId},
                success: function(response){
                    $('#invoices-container').html(response);
                    $('#invoice-list').removeClass('d-none');
                    selectedInvoices = {};
                    updateSummary();
                },
                error: function(){
                    toastr.error('Failed to load invoices');
                }
            });
        } else {
            $('#invoice-list').addClass('d-none');
            selectedInvoices = {};
            updateSummary();
        }
    });

    // Toggle invoice selection
    $(document).on('change', '.invoice-checkbox', function(){
        const invoiceId = $(this).data('invoice-id');
        const card = $(this).closest('.invoice-card');
        
        if($(this).is(':checked')){
            card.addClass('selected');
            const balance = parseFloat($(this).data('balance'));
            selectedInvoices[invoiceId] = {
                balance: balance,
                paying: balance // Default to full payment
            };
            // Enable amount input
            $(`#amount-${invoiceId}`).prop('disabled', false).val(balance.toFixed(2));
        } else {
            card.removeClass('selected');
            delete selectedInvoices[invoiceId];
            $(`#amount-${invoiceId}`).prop('disabled', true).val('');
        }
        
        updateSummary();
    });

    // Update payment amount
    $(document).on('input', '.payment-amount', function(){
        const invoiceId = $(this).data('invoice-id');
        const amount = parseFloat($(this).val()) || 0;
        const balance = parseFloat($(this).data('balance'));
        
        if(amount > balance){
            toastr.warning('Payment amount cannot exceed invoice balance');
            $(this).val(balance.toFixed(2));
            selectedInvoices[invoiceId].paying = balance;
        } else {
            selectedInvoices[invoiceId].paying = amount;
        }
        
        updateSummary();
    });

    // Update summary
    function updateSummary(){
        let count = 0;
        let totalDue = 0;
        let totalPaying = 0;
        
        for(let invoiceId in selectedInvoices){
            count++;
            totalDue += selectedInvoices[invoiceId].balance;
            totalPaying += selectedInvoices[invoiceId].paying;
        }
        
        const remaining = totalDue - totalPaying;
        
        $('#selected-count').text(count);
        $('#total-due').text(totalDue.toFixed(2));
        $('#total-paying').text(totalPaying.toFixed(2));
        $('#remaining-balance').text(remaining.toFixed(2));
        
        // Enable/disable payment button
        if(count > 0 && totalPaying > 0 && $('#payment-method').val()){
            $('#btn-process-payment').prop('disabled', false);
        } else {
            $('#btn-process-payment').prop('disabled', true);
        }
    }

    // Payment method change
    $('#payment-method').on('change', function(){
        updateSummary();
    });

    // Process payment
    $('#btn-process-payment').on('click', function(){
        const customerId = $('#customer').val();
        const paymentMethod = $('#payment-method').val();
        const paymentDate = $('#payment-date').val();
        const paymentNote = $('#payment-note').val();
        
        if(!customerId || !paymentMethod || Object.keys(selectedInvoices).length === 0){
            toastr.error('Please select customer, invoices and payment method');
            return;
        }
        
        const totalPaying = parseFloat($('#total-paying').text());
        if(totalPaying <= 0){
            toastr.error('Total payment amount must be greater than 0');
            return;
        }
        
        // Confirm payment
        Swal.fire({
            title: 'Confirm Payment',
            html: `
                <p>Process payment of <strong>$${totalPaying.toFixed(2)}</strong> for <strong>${Object.keys(selectedInvoices).length}</strong> invoice(s)?</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00a28a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Process Payment',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                processPayment(customerId, paymentMethod, paymentDate, paymentNote);
            }
        });
    });

    function processPayment(customerId, paymentMethod, paymentDate, paymentNote){
        $.ajax({
            url: '../jquery/sales/multiple-payment.php',
            type: 'POST',
            data: {
                process_payment: true,
                customer_id: customerId,
                payment_method: paymentMethod,
                payment_date: paymentDate,
                payment_note: paymentNote,
                invoices: JSON.stringify(selectedInvoices)
            },
            beforeSend: function(){
                $('#btn-process-payment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response){
                response = response.trim();
                
                if(response === 'success'){
                    Swal.fire({
                        title: 'Success!',
                        text: 'Payment processed successfully',
                        icon: 'success',
                        confirmButtonColor: '#00a28a'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    toastr.error('Failed to process payment: ' + response);
                    $('#btn-process-payment').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Process Payment');
                }
            },
            error: function(){
                toastr.error('Network error processing payment');
                $('#btn-process-payment').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Process Payment');
            }
        });
    }
});
</script>

</body>
</html>


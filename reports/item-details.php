<?php include('../path.php'); ?>

<style>
.print-only {
    display: none;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .page-break {
        page-break-before: always;
    }
    
    body {
        font-size: 12px;
    }
    
    .table {
        font-size: 11px;
    }
    
    .card {
        border: 1px solid #000;
        box-shadow: none;
    }
}

.invoice-print {
    background: white;
    padding: 20px;
}

.invoice-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #333;
    padding-bottom: 20px;
}

.invoice-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.invoice-subtitle {
    font-size: 16px;
    color: #666;
}

.invoice-date {
    text-align: right;
    margin-bottom: 20px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: bold;
    border: 1px solid #dee2e6;
}

.table td {
    border: 1px solid #dee2e6;
    vertical-align: middle;
}

.item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.summary-section {
    margin-top: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 5px 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-label {
    font-weight: bold;
}

.summary-value {
    color: #333;
}
</style>

<?php $title = 'Item Details Report'; include(ROOT_PATH . '/inc/header.php'); ?>
<?php $menu = 'Warbixin' ?>
<?php $smenu = 'Item Details Report' ?>

<body class='app'>
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Item Details Report</h1>
                    </div>
                    <div class="col-auto no-print">
                        <div class="page-utilities">
                            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                                <div class="col-auto">
                                    <button class="btn btn-primary" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-success" onclick="exportToExcel()">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row g-3 mb-4 no-print">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="filterForm">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="category" class="form-label">Category</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="">All Categories</option>
                                                <?php 
                                                $stmt = "SELECT * FROM item_category ORDER BY category_name ASC";
                                                $result = mysqli_query($con, $stmt);
                                                if($result) {
                                                    while($row = mysqli_fetch_assoc($result)){
                                                        echo "<option value='{$row['itemcat_id']}'>{$row['category_name']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Error loading categories</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">All Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Search Item</label>
                                            <input type="text" class="form-control" id="search" name="search" placeholder="Search by name or barcode">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div>
                                                <button type="button" class="btn btn-primary" onclick="filterItems()">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                                    <i class="fas fa-times"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Content -->
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-print" id='contentToPrint'>
                            <div class="invoice-header">
                                <center>
                                    <img src="<?= BASE_URL ?>assets/images/logo/ramaas_logo.jpg" height='100' alt="Ramaas Logo">
                                </center>
                                <hr>
                                <div class="invoice-title">Item Details Report</div>
                                <div class="invoice-subtitle">Ramaas Electronics & Cosmetics Centre</div>
                            </div>
                            
                            <div class="invoice-date">
                                <strong>Report Date:</strong> <?= date('d/m/Y H:i:s') ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Image</th>
                                            <th>Item Name</th>
                                            <th>Category</th>
                                            <th>Barcode</th>
                                            <th>Current Qty</th>
                                            <th>Unit</th>
                                            <th>Sale Price</th>
                                            <th>Purchase Price</th>
                                            <th>Profit Margin</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <!-- Items will be loaded here -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="summary-section">
                                <h5>Summary</h5>
                                <div class="summary-row">
                                    <span class="summary-label">Total Items:</span>
                                    <span class="summary-value" id="totalItems">0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Active Items:</span>
                                    <span class="summary-value" id="activeItems">0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Inactive Items:</span>
                                    <span class="summary-value" id="inactiveItems">0</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Total Stock Value:</span>
                                    <span class="summary-value" id="totalStockValue">$0.00</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Average Profit Margin:</span>
                                    <span class="summary-value" id="avgProfitMargin">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        loadItems();
    });

    function loadItems() {
        $.ajax({
             url: 'jquery/reports/item-details.php',
            type: 'POST',
            data: {
                action: 'get_items',
                category: $('#category').val(),
                status: $('#status').val(),
                search: $('#search').val()
            },
            success: function(response) {
                console.log('AJAX Response:', response);
                if (response.success) {
                    displayItems(response.data);
                    updateSummary(response.summary);
                } else {
                    console.error('Error response:', response);
                    toastr.error('Error loading items: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                toastr.error('Error loading items. Please try again.');
            }
        });
    }

    function displayItems(items) {
        let html = '';
        let counter = 1;
        
        items.forEach(function(item) {
            const profitMargin = item.sale_price > 0 ? 
                (((item.sale_price - item.purchase_price) / item.sale_price) * 100).toFixed(1) : 0;
            
            const stockValue = (item.current_qty * item.purchase_price).toFixed(2);
            
            html += `
                <tr>
                    <td>${counter}</td>
                    <td>
                            <img src="${item.image_url}" alt="${item.item_name}" class="item-image" 
                             onerror="this.src='<?= BASE_URL ?>assets/images/products/item_placeholder.png'">
                    </td>
                    <td><strong>${item.item_name}</strong></td>
                    <td>${item.category_name}</td>
                    <td><span class="badge badge-info">${item.barcode}</span></td>
                    <td class="text-center">
                        <span class="badge ${item.current_qty > 10 ? 'badge-success' : item.current_qty > 0 ? 'badge-warning' : 'badge-danger'}">
                            ${item.current_qty}
                        </span>
                    </td>
                    <td>${item.unit_name}</td>
                    <td class="text-right">$${parseFloat(item.sale_price).toFixed(2)}</td>
                    <td class="text-right">$${parseFloat(item.purchase_price).toFixed(2)}</td>
                    <td class="text-right">
                        <span class="badge ${profitMargin > 20 ? 'badge-success' : profitMargin > 10 ? 'badge-warning' : 'badge-danger'}">
                            ${profitMargin}%
                        </span>
                    </td>
                    <td>
                        <span class="badge ${item.status === 'Active' ? 'badge-success' : 'badge-danger'}">
                            ${item.status}
                        </span>
                    </td>
                </tr>
            `;
            counter++;
        });
        
        $('#itemsTableBody').html(html);
    }

    function updateSummary(summary) {
        $('#totalItems').text(summary.total_items);
        $('#activeItems').text(summary.active_items);
        $('#inactiveItems').text(summary.inactive_items);
        $('#totalStockValue').text('$' + parseFloat(summary.total_stock_value).toFixed(2));
        $('#avgProfitMargin').text(summary.avg_profit_margin + '%');
    }

    function filterItems() {
        loadItems();
    }

    function clearFilters() {
        $('#category').val('');
        $('#status').val('');
        $('#search').val('');
        loadItems();
    }

    function exportToExcel() {
        // Create a temporary table for export
        const table = document.getElementById('itemsTable');
        const ws = XLSX.utils.table_to_sheet(table);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Item Details');
        
        // Add company info
        const companyInfo = [
            ['Ramaas Electronics & Cosmetics Centre'],
            ['Item Details Report'],
            ['Generated on: ' + new Date().toLocaleString()],
            ['']
        ];
        
        XLSX.utils.sheet_add_aoa(ws, companyInfo, { origin: 'A1' });
        
        XLSX.writeFile(wb, 'item_details_report_' + new Date().toISOString().split('T')[0] + '.xlsx');
    }

    // Auto-refresh every 5 minutes
    setInterval(function() {
        loadItems();
    }, 300000);
    </script>
</body>
</html>

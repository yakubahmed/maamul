<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
  <link rel="stylesheet" href="assets/stylesheets/theme.min.css" data-skin="default">
  <link rel="stylesheet" href="assets/stylesheets/invoice.css" data-skin="default">

  <style>

  </style>
</head>
<body>

<button id='printButton' class='btn btn-primary' >  Print</button>

  <!-- Page 1 -->
  <div class="page">
    <div class="invoice">
      <div class="header">
        <img src="fpdf/invoice_header.png" style='width:100%;' alt="">
      </div>
      
      <!-- Invoice details and items for page 1 go here -->
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <p>
              <strong>BILL TO:</strong>
              <br>
              <strong>Omar Mohamed</strong> <br>
              Tell: +252616246740 <br>
              Email: N/A <br>
              Address: 
            </p>
          </div>

          <div class="col-md-4 ">
            <p>
              <strong>INVOICE</strong> <br>
              
              Invoice No: <br>
              Sale date: <br>
              Transaction date: 

            </p>
          </div>

          <div class="col-md-12">
            <div class="table table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Item description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Discount</th>
                    <th>Sub Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Iphone Xs Max</td>
                    <td>1,200 $</td>
                    <td>1 Pc</td>
                    <td>-</td>
                    <td>1,200 Pc</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
      
      <div class="footer">
        <p>Page 1</p>
      </div>
    </div>
  </div>

  

  <!-- Add more pages as needed -->

</body>
</html>

<script src="assets/vendor/jquery/jquery.min.js"></script>

<script>
    $(document).ready(function() {
      $("#printButton").on("click", function() {
        // Create a copy of the invoice div
        var printContent = $(".invoice").clone();

        // Create a new window and append the copy of the invoice div to it
        var printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        printWindow.document.write('<link rel="stylesheet" href="assets/stylesheets/theme.min.css">');
        printWindow.document.write('<link rel="stylesheet" href="assets/stylesheets/invoice.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent[0].outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Trigger the print function
       
       setTimeout(() => {
        printWindow.print();
        printWindow.onafterprint = function () {
          printWindow.close();
        };
       }, 4500); 
      });
    });
  </script>
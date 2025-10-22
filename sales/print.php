<?php include('../path.php'); ?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/theme.min.css" data-skin="default">

    <style>
    body {
        height: 842px;
        width: 595px;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
       
    }
    </style>
    
  </head>
  <body>
    <img src="<?= BASE_URL ?>fpdf/invoice_header.png" alt="HEADER" style='width:100%;'>

    <div class="row">
        <div class="col-md-6 my-4">
            <p><Strong>INVOICE TO: </Strong>
            <br>
            Yakub Ahmed
            </p>
            
        </div>
    </div>
  </body>
</html>
<?php include('path.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - 403</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/theme.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/custom.css">
</head>
<body class="app">
    <div class="app">
        <main class="app-main">
            <div class="wrapper">
                <div class="page">
                    <div class="page-inner">
                        <div class="page-section">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-6 text-center">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-4">
                                                    <i class="fas fa-ban text-danger" style="font-size: 4rem;"></i>
                                                </div>
                                                <h1 class="display-4 text-danger">403</h1>
                                                <h2 class="mb-3">Access Denied</h2>
                                                <p class="text-muted mb-4">
                                                    You don't have permission to access this resource. 
                                                    Please contact your administrator if you believe this is an error.
                                                </p>
                                                <div class="btn-group">
                                                    <a href="<?= BASE_URL ?>" class="btn btn-primary">
                                                        <i class="fas fa-home"></i> Go Home
                                                    </a>
                                                    <a href="javascript:history.back()" class="btn btn-secondary">
                                                        <i class="fas fa-arrow-left"></i> Go Back
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="<?= BASE_URL ?>assets/javascript/theme.min.js"></script>
</body>
</html>




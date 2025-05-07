<?php
session_start();

// Clear the cart after successful purchase
$_SESSION['cart'] = [];
require_once 'includes/header.php';
$pageTitle = 'Order Confirmation';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Furnixar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header Section -->
  

    <!-- Order Confirmation Section -->
    <section class="order-confirmation py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h1 class="mb-4">Thank You for Your Order!</h1>
                            <p class="lead mb-4">Your order has been successfully placed. We'll send you an email with the order details and tracking information.</p>
                            
                            <div class="order-details mb-4">
                                <h5 class="mb-3">Order Details</h5>
                                <p>Order Number: #<?php echo rand(100000, 999999); ?></p>
                                <p>Estimated Delivery: <?php echo date('F j, Y', strtotime('+5 days')); ?></p>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
                                <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once 'includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html> 
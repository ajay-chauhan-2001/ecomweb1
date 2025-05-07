<?php
// Start the session at the VERY BEGINNING before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'functions.php';

// Set a default page title if not set
$pageTitle = isset($pageTitle) ? $pageTitle . ' - FurniCraft' : 'FurniCraft';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</head>

<body>
    <!-- Header Section -->
    <header class="header bold-header shadow-sm rounded-bottom">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid d-flex justify-content-between align-items-center py-2">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <img src="assets/images/logo.png" alt="FurniCraft Logo" style="width: 120px; height: 60px;">
                </a>
                <div class="d-flex align-items-center gap-3">
                    <ul class="navbar-nav flex-row gap-2 me-3">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        $navLinks = [
                            'index.php' => 'Home',
                            'shop.php' => 'Shop',
                            'gallery.php' => 'Gallery',
                            'about.php' => 'About',
                            'contact.php' => 'Contact',
                        ];
                        foreach ($navLinks as $file => $label): ?>
                            <li class="nav-item">
                                <a class="nav-link fw-bold text-white <?php echo $currentPage === $file ? 'active' : ''; ?>" href="<?php echo $file; ?>">
                                    <?php echo $label; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="cart.php" class="btn btn-warning position-relative me-2">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <?php $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        <?php if ($cartCount > 0): ?>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.9em;">
    <?php echo $cartCount; ?>
</span>
                            
                        <?php endif; ?>
                    </a>

                    <?php if (isLoggedIn()): ?>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle fw-bold px-3" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars(getCurrentUser()['name']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-dark fw-bold px-3">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Messages Section -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Start -->
    <main class="container py-4" style="max-width: 100%;">
    <script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cart_count;
        }
    });
});
</script>
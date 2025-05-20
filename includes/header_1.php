<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' - FurniCraft' : 'FurniCraft';

// function isLoggedIn() {
//     return isset($_SESSION['user']);
// }

// function getCurrentUser() {
//     return $_SESSION['user'] ?? ['name' => 'Guest'];
// }

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon.ico" />
    <link rel="icon" type="image/png" sizes="16x16" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-180x180.png" />
    <link rel="icon" sizes="192x192" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-192x192.png" />

    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="assets/css/style.css" />

    <style>
    /* Top bar */
    .topbar {
        background: #f8f9fa;
        font-size: 0.875rem;
        padding: 0.3rem 0;
        border-bottom: 1px solid #ddd;
    }
    .topbar a {
        color: #333;
        text-decoration: none;
        margin-right: 1rem;
    }
    .topbar a:hover {
        text-decoration: underline;
    }
    /* Navbar styles */
    .navbar-nav .nav-link {
        font-weight: 600;
        color: #333;
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .dropdown-menu a:hover {
        color: #007bff;
    }
    /* Dropdown */
    .dropdown-menu {
        min-width: 200px;
        border-radius: 0;
        border-color: #ddd;
    }
    /* Search bar */
    .search-bar {
        max-width: 600px;
        width: 100%;
    }
    /* Sticky shadow */
    header.sticky-top.scrolled {
        box-shadow: 0 3px 8px rgb(0 0 0 / 0.15);
        transition: box-shadow 0.3s ease-in-out;
    }
    /* Cart badge */
    .cart-badge {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 0.75rem;
        padding: 0.25em 0.4em;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
    }
    </style>
</head>
<body>

<!-- Top Info Bar -->
<div class="topbar d-flex justify-content-between align-items-center px-3">
    <div>
        <a href="tel:+911234567890"><i class="fas fa-phone"></i> +91 12345 67890</a>
        <a href="mailto:support@furnicraft.in"><i class="fas fa-envelope"></i> support@furnicraft.in</a>
    </div>
    <div>
        <a href="track_order.php">Track Order</a>
        <a href="contact.php">Contact Us</a>
    </div>
</div>

<!-- Main Navbar -->
<header class="sticky-top bg-white">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/header_logo.png" alt="FurniCraft Logo" style="height: 60px;" />
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Collapse -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <!-- Left Navigation Menu -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                            <a class="nav-link <?php echo $currentPage === $file ? 'active text-primary' : 'text-dark'; ?>" href="<?php echo $file; ?>">
                                <?php echo $label; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Center Search Bar -->
                <form class="d-flex mx-auto my-2 my-lg-0 search-bar" action="shop.php" method="get" role="search">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search furniture, sofas, beds..." aria-label="Search" required />
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <!-- Right User & Cart -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Wishlist -->
                    <a href="wishlist.php" class="btn btn-outline-danger position-relative" title="Wishlist">
                        <i class="fas fa-heart fa-lg"></i>
                    </a>

                    <!-- Cart -->
                    <a href="cart.php" class="btn btn-outline-warning position-relative" title="Cart" style="position: relative;">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- User dropdown -->
                    <?php if (isLoggedIn()): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                <?php echo htmlspecialchars(getCurrentUser()['name']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="orders_history.php"><i class="fas fa-box me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary fw-bold">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Sticky shadow on scroll -->
<script>
window.addEventListener('scroll', function () {
    const header = document.querySelector('header.sticky-top');
    if (window.scrollY > 10) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>

<!-- Bootstrap & dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

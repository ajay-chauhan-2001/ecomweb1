<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' - FurniCraft' : 'FurniCraft';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-180x180.png">
    <link rel="icon" sizes="192x192" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-192x192.png">

    <!-- Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Scrolling Announcement Bar */
        .announcement-bar {
            background-color: #eaf1f5;
            color: #000;
            white-space: nowrap;
            overflow: hidden;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
        }
        .scrolling-text {
            display: inline-block;
            padding-left: 100%;
            animation: scrollText 25s linear infinite;
        }
        .scrolling-text:hover {
            animation-play-state: paused;
        }
        @keyframes scrollText {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Preloader */
        #globalPreloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader-content img {
            width: 150px;
            max-width: 60vw;
            height: auto;
            animation: bounce 2s infinite;
        }

        .preloader-content p {
            margin-top: 15px;
            font-size: 1.25rem;
            color: #333;
            animation: fadeText 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes fadeText {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column !important;
            }
            .navbar-brand img {
                width: 100px;
                height: auto;
            }
            .dropdown-menu {
                position: static;
                float: none;
            }
        }
    </style>
</head>
<body>

<!-- ✅ Scrolling Announcement Bar -->
<div class="announcement-bar py-2">
    <div class="container">
        <div class="scrolling-text">
            <span>🔥 10% OFF on all furniture | </span>
            <span>🚚 Free Shipping on orders above ₹ 24999 </span>
           
            
        </div>
    </div>
</div>

<!-- Header Section -->
<header class="header bold-header shadow-sm rounded-bottom bg-info">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/header_logo.png" alt="FurniCraft Logo" style="width: 120px; height: 60px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
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
                            <a class="nav-link fw-bold <?php echo $currentPage === $file ? 'text-warning active' : 'text-white'; ?>" href="<?php echo $file; ?>">
                                <?php echo $label; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <a href="cart.php" class="btn btn-warning position-relative">
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
                            <button class="btn btn-light dropdown-toggle fw-bold d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                <?php echo htmlspecialchars(getCurrentUser()['name']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="orders_history.php"><i class="bi bi-bag me-2"></i>My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-light fw-bold">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Flash Messages -->
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

<!-- ✅ Preloader -->
<div id="globalPreloader">
    <div class="preloader-content text-center">
        <img src="assets/images/header.jpeg" alt="Loading...">
        <p>Loading your experience...</p>
    </div>
</div>
<script>
window.addEventListener('load', function () {
    const preloader = document.getElementById('globalPreloader');
    preloader.style.opacity = '0';
    preloader.style.visibility = 'hidden';
    setTimeout(() => {
        preloader.style.display = 'none';
    }, 600);
});
</script>

<!-- Main Content Start -->
<main class="container py-4" style="max-width: 100%;">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateCartCount() {
    $.ajax({
        url: 'ajax/get_cart_count.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#cart-count').text(response.count);
            }
        }
    });
}
$(document).ready(function() {
    updateCartCount();
});
</script>

<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'functions.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' - FurniCraft' : 'FurniCraft';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle); ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/favicon/favicon-32x32.png" />

  <!-- CSS Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />

  <style>
    /* Sticky Navigation Bar */
    .sticky-menu {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1050;
      background-color: #0d6efd;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Add padding to body to prevent content being hidden under fixed menu */
    body {
      padding-top: 60px; /* Adjust if your navbar height changes */
    }

    .announcement-bar {
      background-color: black;
      color: white;
      font-weight: 600;
      font-size: 1rem;
    }

    .middle-header {
      transition: background-color 0.4s ease;
    }

    .scroll-item {
      transition: opacity 0.5s;
    }

    #globalPreloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #fff;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #globalPreloader img {
      width: 100px;
    }

    .nav-link.active {
      color: #ffc107 !important;
    }
  </style>
</head>
<body>

<!-- ✅ Preloader -->
<div id="globalPreloader">
  <img src="assets/images/header.jpeg" alt="Loading..." />
</div>

<!-- ✅ Announcement Bar -->
<div class="announcement-bar py-2 text-center">
  <div class="scrolling-text-wrapper">
    <span class="scroll-item">🔥 10% OFF on all furniture</span>
    <span class="scroll-item d-none">🚚 Free Shipping on orders above ₹ 24999</span>
    <span class="scroll-item d-none">🛠️ Customized furniture available</span>
    <span class="scroll-item d-none">📦 Easy returns & nationwide delivery</span>
  </div>
</div>

<!-- ✅ Middle Header (Logo, Search, Cart, Login) -->
<div id="middleHeader" class="middle-header py-3 border-bottom shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img id="furniLogo" src="assets/images/header.jpeg" alt="FurniCraft" style="height: 60px;" crossorigin="anonymous" />
    </a>

    <!-- Search -->
    <form action="shop.php" method="GET" class="d-none d-md-flex w-50">
      <input type="text" name="search" class="form-control me-2" placeholder="Search furniture..." />
      <button type="submit" class="btn btn-outline-dark">Search</button>
    </form>

    <!-- Cart & User -->
    <div class="d-flex align-items-center gap-2">
      <a href="cart.php" class="btn btn-warning position-relative">
        <i class="fas fa-shopping-cart"></i>
        <?php $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
        <?php if ($cartCount > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
        <?php endif; ?>
      </a>
      <?php if (isLoggedIn()): ?>
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars(getCurrentUser()['name']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="orders_history.php">My Orders</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-dark">Login</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ✅ Sticky Navigation Menu -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-menu">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
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
        foreach ($navLinks as $file => $label):
        ?>
          <li class="nav-item">
            <a class="nav-link fw-bold <?= $currentPage === $file ? 'active' : ''; ?>" href="<?= $file; ?>"><?= $label; ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Flash Messages -->
<div class="container mt-3">
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>
</div>

<!-- ✅ Main Content -->
<main class="container py-4" style="max-width: 100%;">
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
<script>
  // Preloader
  window.addEventListener('load', () => {
    const preloader = document.getElementById('globalPreloader');
    preloader.style.opacity = '0';
    preloader.style.visibility = 'hidden';
    setTimeout(() => {
      preloader.style.display = 'none';
    }, 500);
  });

  // Scrolling announcement bar
  $(document).ready(function () {
    let currentIndex = 0;
    const items = $('.scroll-item');
    const totalItems = items.length;

    function showNextItem() {
      items.eq(currentIndex).addClass('d-none');
      currentIndex = (currentIndex + 1) % totalItems;
      items.eq(currentIndex).removeClass('d-none');
    }

    setInterval(showNextItem, 4000);
  });

  // Dynamic header background color using Color Thief
  document.addEventListener('DOMContentLoaded', function () {
    const colorThief = new ColorThief();
    const img = document.getElementById('furniLogo');

    if (img.complete) applyHeaderColor();
    else img.addEventListener('load', applyHeaderColor);

    function applyHeaderColor() {
      try {
        const color = colorThief.getColor(img);
        const rgb = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;
        document.getElementById('middleHeader').style.backgroundColor = rgb;
      } catch (e) {
        console.warn('Color extraction failed:', e);
      }
    }
  });
</script>

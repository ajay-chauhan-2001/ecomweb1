<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-2 sidebar d-flex flex-column align-items-start animate__animated animate__fadeInLeft">
    <div class="mb-4 w-100 text-center">
        <h4 class="text-white mb-3">Admin Panel</h4>
    </div>

    <ul class="nav flex-column w-100">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>" href="products.php">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'categories.php' ? 'active' : ''; ?>" href="categories.php">
                <i class="fas fa-tags"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>" href="orders.php">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page === 'users.php' ? 'active' : ''; ?>" href="users.php">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
    </ul>

    <div class="logout-section mt-auto w-100">
        <hr class="bg-secondary">
        <a class="nav-link logout-link" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<!-- Animate.css for sidebar animation -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
.sidebar {
    min-height: 100vh;
    background-color: #343a40;
    padding: 20px 15px;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    transition: all 0.4s ease-in-out; /* Smooth transition */
}

.nav-link {
    color: #ffffff;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: #495057;
    color: #ffffff;
    transform: translateX(5px); /* Hover move effect */
}

.nav-link.active {
    background-color: #007bff;
    color: #ffffff;
    font-weight: bold;
}

.logout-section {
    margin-top: auto;
}

.logout-link {
    color: #ff4d4d;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.logout-link:hover {
    background-color: #ff4d4d;
    color: #ffffff;
    transform: translateX(5px); /* Move on hover */
}

/* Optional: Add soft bounce on active */
.nav-link:active {
    transform: scale(0.95);
}
</style>

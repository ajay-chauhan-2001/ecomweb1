<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get statistics
$conn = getDBConnection();

// Total products
$stmt = $conn->query("SELECT COUNT(*) as total FROM products");
$totalProducts = $stmt->fetch_assoc()['total'];

// Total categories
$stmt = $conn->query("SELECT COUNT(*) as total FROM categories");
$totalCategories = $stmt->fetch_assoc()['total'];

// Total orders
$stmt = $conn->query("SELECT COUNT(*) as total FROM orders");
$totalOrders = $stmt->fetch_assoc()['total'];

// Total users
$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
$totalUsers = $stmt->fetch_assoc()['total'];

// Total revenue
$stmt = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
$totalRevenueRow = $stmt->fetch_assoc();
$totalRevenue = $totalRevenueRow['total'] ?? 0;

// Recent orders
$stmt = $conn->query("SELECT o.*, u.name as customer_name 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     ORDER BY o.created_at DESC 
                     LIMIT 5");
$recentOrders = [];
if ($stmt) {
    while ($row = $stmt->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

$page_title = "Admin Dashboard";
require_once 'includes/header.php';
?>

<!-- Sidebar -->
<?php require_once 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="col-md-10 main-content">
    <?php require_once 'includes/profile.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <div class="text-muted">
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
       
        <div class="col-md-3">
            <div class="stat-card primary" style="cursor: pointer; color: white;" onclick="location.href='products.php';">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $totalProducts; ?></h3>
                        <p>Total Products</p>
                    </div>
                    <i class="fas fa-box fa-2x"></i>
                </div>
            </div>
        </div>

        
        <div class="col-md-3">
            <div class="stat-card info" style="cursor: pointer; color: white;" onclick="location.href='categories.php';">   
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Total Categories</p>
                    </div>
                    <i class="fas fa-tags fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        <div class="stat-card success" style="cursor: pointer; color: white;" onclick="location.href='orders.php';">

           
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $totalOrders; ?></h3>
                        <p >Total Orders</p>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        <div class="stat-card warning" style="cursor: pointer; color: white;" onclick="location.href='users.php';">
            
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><?php echo $totalUsers; ?></h3>
                        <p style="color: white;">Total Users</p>
                    </div>
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <!-- <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>₹<?php echo number_format($totalRevenue, 2); ?></h3>
                        <p style="color: white;">Total Revenue</p>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Recent Orders -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Orders</h5>
            <a href="orders.php" class="btn btn-sm btn-primary">View All Orders</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $order['status'] === 'pending' ? 'warning' : 
                                            ($order['status'] === 'completed' ? 'success' : 'secondary'); 
                                    ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="order-details.php?id=<?php echo $order['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
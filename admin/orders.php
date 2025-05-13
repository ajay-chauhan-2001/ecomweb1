<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];

    // Fetch current order status first
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    if ($current_status !== 'delivered') {
        // Allow update only if not delivered
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();

        header('Location: orders.php?message=Order status updated');
        exit;
    } else {
        header('Location: orders.php?error=Cannot change status of delivered order');
        exit;
    }
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total orders
$totalResult = $conn->query("SELECT COUNT(*) as total FROM orders");
$totalRow = $totalResult->fetch_assoc();
$totalOrders = $totalRow['total'];
$totalPages = ceil($totalOrders / $limit);

// Get orders with pagination
$query = "SELECT o.*, u.name as customer_name, u.email as customer_email 
          FROM orders o
          JOIN users u ON o.user_id = u.id
          ORDER BY o.created_at DESC
          LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$orders = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$page_title = "Manage Orders";
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - FurniCraft Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php require_once 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Orders</h2>
                </div>

            <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success" id="statusMessage"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" id="statusMessage"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $serial = ($page - 1) * $limit + 1;
                                    foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo $serial++; ?></td>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                                            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" <?php echo $order['status'] === 'delivered' ? 'disabled' : ''; ?>>
                                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                    <?php if ($order['status'] !== 'delivered'): ?>
                                                        <input type="hidden" name="update_status" value="1">
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .fade {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Auto-hide alert after 3 seconds
    setTimeout(() => {
        const statusMessage = document.getElementById('statusMessage');
        if (statusMessage) {
            statusMessage.classList.add('fade');
            statusMessage.style.transition = 'opacity 0.5s ease-out';
            statusMessage.style.opacity = '0';
            setTimeout(() => statusMessage.remove(), 500);
        }
    }, 3000);
</script>

</body>
</html>

<?php require_once 'includes/footer.php'; ?>

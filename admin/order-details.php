<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Get order ID from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
    $stmt->close();

    header('Location: order-details.php?id=' . $order_id . '&message=Order status updated successfully');
    exit;
}

// Get order details with user information
$stmt = $conn->prepare("SELECT o.*, u.name as customer_name, u.email, u.phone, u.address 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        WHERE o.id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: orders.php?error=Order not found');
    exit;
}

// Get order items
$stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.price 
                        FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$page_title = "Order Details";
require_once 'includes/header.php';
?>

<!-- Sidebar -->
<?php require_once 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="col-md-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Details #<?php echo $order_id; ?></h2>
        <a href="orders.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Date:</strong> <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <form method="POST" class="d-inline">
                            <select name="status" class="form-select d-inline w-auto" onchange="this.form.submit()">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </div>
                    <div class="mb-3">
                        <strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($order_items)): ?>
                                    <?php foreach ($order_items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No products found for this order.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

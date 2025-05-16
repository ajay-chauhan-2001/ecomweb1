<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid order ID.');
}

$orderId = (int)$_GET['id'];
$conn = getDBConnection();
$user = getCurrentUser(); // Ensure user is logged in

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $orderId, $user['id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch order items
$stmtItems = $conn->prepare("
    SELECT oi.*, p.name AS product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmtItems->bind_param('i', $orderId);
$stmtItems->execute();
$items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch user shipping info from users table
$stmtUser = $conn->prepare("SELECT name, phone, address, city, state, zip_code FROM users WHERE id = ?");
$stmtUser->bind_param("i", $order['user_id']);
$stmtUser->execute();
$userInfo = $stmtUser->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $orderId ?> - FurniCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-summary { background: #f8f9fa; padding: 20px; border-radius: 8px; }
        .section-title { font-size: 20px; font-weight: 600; margin-bottom: 15px; }
        .back-link { margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>

<div class="container py-5">
    <a href="order-history.php" class="back-link text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Order History</a>
    
    <h2 class="mb-4">Order #<?= $orderId ?></h2>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-6 mb-4">
            <div class="order-summary">
                <div class="section-title">Order Information</div>
                <p><strong>Order Date:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?></p>
                <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
                <p><strong>Total:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="col-lg-6 mb-4">
            <div class="order-summary">
                <div class="section-title">Shipping Information</div>
                <p><strong><?= htmlspecialchars($userInfo['name']) ?></strong></p>
                <p><?= htmlspecialchars($userInfo['address']) ?></p>
                <p><?= htmlspecialchars($userInfo['city']) ?>, <?= htmlspecialchars($userInfo['state']) ?> - <?= htmlspecialchars($userInfo['zip_code']) ?></p>
                <p>Phone: <?= htmlspecialchars($userInfo['phone']) ?></p>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="mt-4">
        <div class="section-title">Order Items</div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th width="10%">Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Grand Total:</th>
                    <th>$<?= number_format($order['total_amount'], 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3 text-end">
            <a href="invoice.php?id=<?= $orderId ?>" class="btn btn-outline-primary">
                <i class="bi bi-printer"></i> Print Invoice
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

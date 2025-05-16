<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid order ID.');
}

$orderId = (int)$_GET['id'];
$conn = getDBConnection();
$user = getCurrentUser(); // Ensure user is logged in

// Fetch order with user info
$stmt = $conn->prepare("
    SELECT o.*, u.name AS user_name, u.email, u.phone, u.address AS user_address
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->bind_param('ii', $orderId, $user['id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die('Order not found.');
}

// Fetch items
$stmtItems = $conn->prepare("SELECT oi.*, p.name AS product_name 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$stmtItems->bind_param('i', $orderId);
$stmtItems->execute();
$items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $orderId ?> - FurniCraft</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { padding: 40px; font-size: 14px; }
        .invoice-box { border: 1px solid #eee; padding: 30px; border-radius: 10px; }
        .invoice-title { font-size: 28px; font-weight: bold; margin-bottom: 20px; }
        .table td, .table th { vertical-align: middle; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container invoice-box">
    <div class="invoice-title text-center">
        Invoice #<?= $orderId ?>
    </div>
    <hr>

    <div class="row mb-4">
        <div class="col-sm-6">
            <h6>Billing Information:</h6>
            <strong><?= htmlspecialchars($order['user_name']) ?></strong><br>
            <?= htmlspecialchars($order['email']) ?><br>
            <?= htmlspecialchars($order['user_address']) ?><br>
            Phone: <?= htmlspecialchars($order['phone']) ?>
        </div>
        <div class="col-sm-6 text-end">
            <h6>Order Details:</h6>
            <strong>Order Date:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?><br>
            <strong>Status:</strong> <?= ucfirst($order['status']) ?><br>
            <strong>Total:</strong> $<?= number_format($order['total_amount'], 2) ?>
        </div>
    </div>

    <h6>Order Items:</h6>
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

    <div class="text-center mt-4 no-print">
        <a href="javascript:window.print()" class="btn btn-outline-primary">🖨️ Print Invoice</a>
        <!-- Optional PDF generation link -->
        <!-- <a href="generate-pdf.php?id=<?= $orderId ?>" class="btn btn-primary">⬇️ Download PDF</a> -->
    </div>
</div>
</body>
</html>

<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int)$_GET['id'];
$user = getCurrentUser();

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Order not found or access denied.</div></div>";
    include 'includes/footer.php';
    exit;
}

$order = $result->fetch_assoc();

// Fetch ordered items
$stmt_items = $conn->prepare("SELECT oi.*, p.name AS product_name, p.image AS product_image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?php echo $order_id; ?> Details - FurniCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .order-summary th,
        .order-summary td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Order #<?php echo $order_id; ?></h3>
        <div>
            <a href="invoice.php?id=<?php echo $order_id; ?>" class="btn btn-outline-secondary me-2" target="_blank">
                <i class="fa fa-file-invoice"></i> Download Invoice
            </a>
            <!-- <a href="reorder.php?id=<?php echo $order_id; ?>" class="btn btn-outline-success">
                <i class="fa fa-sync-alt"></i> Reorder
            </a> -->
        </div>
    </div>

    <!-- Order Status and Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Order Information</h5>
            <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($order['created_at'])); ?></p>
            <p><strong>Status:</strong> <span class="badge bg-info text-dark"><?php echo ucfirst($order['status']); ?></span></p>
        </div>
        <div class="col-md-6">
            <h5>Shipping Information</h5>
            <p><?php echo htmlspecialchars($order['name']); ?></p>
            <p><?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <p><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' - ' . $order['shipping_zip']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
        </div>
    </div>

    <!-- Ordered Items -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered order-summary">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <img src="uploads/<?php echo htmlspecialchars($item['product_image']); ?>" class="product-img me-2">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Subtotal</th>
                    <td>$<?php echo number_format($order['subtotal'], 2); ?></td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Shipping</th>
                    <td>$<?php echo number_format($order['shipping_fee'], 2); ?></td>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Shipment Tracking -->
    <div class="card">
        <div class="card-header">
            <h5>Shipment Tracking</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($order['tracking_number'])): ?>
                <p><strong>Carrier:</strong> <?php echo htmlspecialchars($order['shipping_carrier']); ?></p>
                <p><strong>Tracking Number:</strong> <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                <a href="<?php echo htmlspecialchars($order['tracking_url']); ?>" class="btn btn-outline-primary" target="_blank">
                    Track Shipment
                </a>
            <?php else: ?>
                <p class="text-muted">Tracking details will be available once your order is shipped.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

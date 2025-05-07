<?php
session_start();
require_once '../config/database.php';

// User / Guest detection
if (isset($_SESSION['user_id'])) {
    $userId = (int)$_SESSION['user_id'];
    $sessionId = null;
} else {
    $sessionId = $_SESSION['guest_id'] ?? '';
    $userId = null;
}

// Validate inputs
if (!isset($_POST['product_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$productId = (int)$_POST['product_id'];
$action = $_POST['action'];

// Find the cart item
$query = "SELECT * FROM cart WHERE product_id = ? AND " . ($userId !== null ? "user_id = ?" : "session_id = ?");
$stmt = $conn->prepare($query);

if ($userId !== null) {
    $stmt->bind_param('ii', $productId, $userId);
} else {
    $stmt->bind_param('is', $productId, $sessionId);
}
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

$new_quantity = $item['quantity'];

if ($action === 'increase') {
    $new_quantity++;
} elseif ($action === 'decrease' && $new_quantity > 1) {
    $new_quantity--;
}

// Update cart
$updateQuery = "UPDATE cart SET quantity = ? WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param('ii', $new_quantity, $item['id']);
$updateStmt->execute();

// Fetch product pricing
$productQuery = "SELECT price, sale_price FROM products WHERE id = ?";
$productStmt = $conn->prepare($productQuery);
$productStmt->bind_param('i', $productId);
$productStmt->execute();
$productResult = $productStmt->get_result();
$product = $productResult->fetch_assoc();

$price = ($product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
$subtotal = $price * $new_quantity;

// Calculate updated order summary
$totalQuery = "SELECT c.quantity, p.price, p.sale_price 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE " . ($userId !== null ? "c.user_id = ?" : "c.session_id = ?");
$totalStmt = $conn->prepare($totalQuery);

if ($userId !== null) {
    $totalStmt->bind_param('i', $userId);
} else {
    $totalStmt->bind_param('s', $sessionId);
}
$totalStmt->execute();
$totalResult = $totalStmt->get_result();

$subTotalBeforeDiscount = 0;
$totalDiscount = 0;
$finalTotal = 0;

while ($row = $totalResult->fetch_assoc()) {
    $mainPrice = $row['price'];
    $salePrice = $row['sale_price'];
    $quantity = $row['quantity'];

    $productSubtotal = $mainPrice * $quantity;
    $subTotalBeforeDiscount += $productSubtotal;

    if ($salePrice > 0) {
        $discountAmount = ($mainPrice - $salePrice) * $quantity;
        $totalDiscount += $discountAmount;
        $finalTotal += $salePrice * $quantity;
    } else {
        $finalTotal += $mainPrice * $quantity;
    }
}

// Final JSON Response
echo json_encode([
    'success' => true,
    'new_quantity' => $new_quantity,
    'new_subtotal' => $subtotal,
    'order_summary' => [
        'subtotal' => $subTotalBeforeDiscount,
        'discount' => $totalDiscount,
        'total' => $finalTotal
    ]
]);
exit;
?>

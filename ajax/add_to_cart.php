<?php
session_start();
require_once '../config/database.php'; // db connection

header('Content-Type: application/json'); // <-- important for JSON response

// Check if user is logged in, otherwise assign guest session_id
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if (!$user_id) {
    if (!isset($_SESSION['guest_id'])) {
        $randomNumber = random_int(10000, 99999);
        $datePart = date('Ymd');
        $_SESSION['guest_id'] = 'guest_' . $datePart . '_' . $randomNumber;
    }
    $session_id = $_SESSION['guest_id'];
} else {
    $session_id = null;
}

// Get product_id and quantity from AJAX
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
    exit;
}

// Fetch product details
$stmt = $conn->prepare("SELECT stock, price, sale_price FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if ($quantity > $product['stock']) {
    echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds stock.']);
    exit;
}

// Calculate effective price
$price = $product['price'];
$sale_price = ($product['sale_price'] > 0) ? $product['sale_price'] : null;

// Check if product already in cart
if ($user_id) {
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
} else {
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
}
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_assoc();
$stmt->close();

if ($cart) {
    // Update quantity (add to existing)
    $new_quantity = $cart['quantity'] + $quantity;
    if ($user_id) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("isi", $new_quantity, $session_id, $product_id);
    }
} else {
    // Insert new item
    if ($user_id) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price, sale_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $user_id, $product_id, $quantity, $price, $sale_price);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity, price, sale_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siidd", $session_id, $product_id, $quantity, $price, $sale_price);
    }
}

if ($stmt->execute()) {
    // ✅ Success - now fetch new cart count
    if ($user_id) {
        $countStmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id = ?");
        $countStmt->bind_param("i", $user_id);
    } else {
        $countStmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE session_id = ?");
        $countStmt->bind_param("s", $session_id);
    }
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $row = $countResult->fetch_assoc();
    $cart_count = (int)($row['total'] ?? 0);
    $countStmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully.',
        'cart_count' => $cart_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update cart.']);
}

$stmt->close();
$conn->close();
?>

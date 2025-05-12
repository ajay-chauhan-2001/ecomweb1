<?php
session_start();
require_once '../config/database.php'; // adjust path

header('Content-Type: application/json');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
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

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity.']);
    exit;
}

$stmt = $conn->prepare("SELECT stock, price, sale_price FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if ($quantity > $product['stock']) {
    echo json_encode(['success' => false, 'message' => 'Requested quantity exceeds stock.']);
    exit;
}

$price = $product['price'];
$sale_price = ($product['sale_price'] > 0) ? $product['sale_price'] : null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
} else {
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
    $stmt->bind_param("si", $session_id, $product_id);
}
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($cart) {
    $new_quantity = $cart['quantity'] + $quantity;
    if ($user_id) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param("isi", $new_quantity, $session_id, $product_id);
    }
} else {
    if ($user_id) {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price, sale_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $user_id, $product_id, $quantity, $price, $sale_price);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity, price, sale_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siidd", $session_id, $product_id, $quantity, $price, $sale_price);
    }
}

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Cart updated successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update cart.'
    ]);
}

$stmt->close();
$conn->close();
?>

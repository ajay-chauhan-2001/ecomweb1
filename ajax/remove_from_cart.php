<?php
session_start();
require_once '../config/database.php';

$response = ['success' => false];

if (isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];

    if (isset($_SESSION['user_id'])) {
        $userId = (int)$_SESSION['user_id'];
        $sessionId = null;
    } else {
        if (!isset($_SESSION['guest_id'])) {
            $randomNumber = random_int(10000, 99999);
            $datePart = date('Ymd');
            $_SESSION['guest_id'] = 'guest_' . $datePart . '_' . $randomNumber;
        }
        $sessionId = $_SESSION['guest_id'];
        $userId = null;
    }

    $where = $userId !== null ? 'user_id = ?' : 'session_id = ?';
    $param = $userId !== null ? $userId : $sessionId;

    // Delete from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND $where");
    $stmt->bind_param('is', $productId, $param);
    $stmt->execute();
    $stmt->close();

    // Recalculate total
    $stmt = $conn->prepare("
        SELECT c.quantity, p.price, p.sale_price
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE $where
    ");
    $stmt->bind_param('s', $param);
    $stmt->execute();
    $result = $stmt->get_result();

    $newTotal = 0;
    while ($row = $result->fetch_assoc()) {
        $price = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
        $newTotal += $price * $row['quantity'];
    }
    $stmt->close();

    $response = [
        'success' => true,
        'new_total' => $newTotal
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

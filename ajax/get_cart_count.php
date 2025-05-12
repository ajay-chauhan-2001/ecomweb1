<?php
session_start();
require_once '../config/database.php'; // Adjust path if needed

header('Content-Type: application/json');

try {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $sessionId = isset($_SESSION['guest_id']) ? $_SESSION['guest_id'] : session_id();

    if ($userId > 0) {
        $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
    } else {
        $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total FROM cart WHERE session_id = ?");
        $stmt->bind_param("s", $sessionId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'count' => (int)$row['total']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'count' => 0,
        'message' => $e->getMessage() // helpful for debugging
    ]);
}
?>

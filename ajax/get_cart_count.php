<?php
session_start();
require_once '../config/database.php'; // adjust the path

header('Content-Type: application/json');

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$sessionId = session_id();

try {
    if ($userId > 0) {
        $stmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
    } else {
        $stmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE session_id = ?");
        $stmt->bind_param("s", $sessionId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $count = $row && $row['total'] ? (int)$row['total'] : 0;

    echo json_encode([
        'status' => 'success',
        'count' => $count
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'count' => 0
    ]);
}
?>

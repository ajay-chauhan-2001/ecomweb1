<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid Category ID.";
    header('Location: categories.php');
    exit();
}

$categoryId = intval($_GET['id']); // Make sure it's an integer (avoid SQL injection)

// Optional: Verify if the category exists first
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Category not found.";
    header('Location: categories.php');
    exit();
}

// If exists, proceed to delete
$deleteSql = "DELETE FROM categories WHERE id = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $categoryId);

if ($deleteStmt->execute()) {
    $_SESSION['success'] = "Category deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete category.";
}

header('Location: categories.php');
exit();
?>

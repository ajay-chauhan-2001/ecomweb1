<?php
require_once '../includes/functions.php';

$conn = getDBConnection();

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM contact_messages WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_contact.php?success=1");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>

<?php
// save_contact.php

require_once __DIR__ . '/config/database.php'; // Corrected path

$conn = getDBConnection();

// Get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Simple security: Escape strings
$name = $conn->real_escape_string($name);
$email = $conn->real_escape_string($email);
$subject = $conn->real_escape_string($subject);
$message = $conn->real_escape_string($message);

// Insert into database
$sql = "INSERT INTO contact_messages (name, email, subject, message, status, created_at) 
        VALUES ('$name', '$email', '$subject', '$message', 0, NOW())";

if ($conn->query($sql) === TRUE) {
    http_response_code(200);
    echo "Success";
} else {
    http_response_code(500);
    echo "Error: " . $conn->error;
}

$conn->close();
?>

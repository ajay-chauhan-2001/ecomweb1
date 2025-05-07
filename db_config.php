<?php
// Database configuration for Hostinger
define('DB_HOST', 'localhost');
define('DB_USER', 'u139640761_furnicraft');
define('DB_PASS', 'Furnicraft@2025'); // Replace with your actual password
define('DB_NAME', 'u139640761_furnicraft');

// Create global database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($conn->connect_error) {
    // Log the detailed error
    error_log("Database Connection Error: " . $conn->connect_error);
    error_log("Error Code: " . $conn->connect_errno);
    
    // Display a user-friendly error message
    die("Database connection failed. Please try again later.");
} else {
    // Set charset to utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
        error_log("Error loading character set utf8mb4: " . $conn->error);
        die("Database connection failed. Please try again later.");
    }
    
    // Store the connection in a global variable
    $GLOBALS['conn'] = $conn;

    // Log successful connection
    error_log("Database connection established successfully (conn)");
}

// Function to get database connection (for backward compatibility)
function getDBConnection() {
    return $GLOBALS['conn'];
}
?>

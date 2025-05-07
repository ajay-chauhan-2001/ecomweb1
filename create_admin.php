<?php
require_once 'config/database.php';

try {
    $db = getDBConnection();
    
    // Create admin user
    $name = 'Admin';
    $email = 'admin@furnicraft.com';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $password]);
    
    echo "Admin user created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 
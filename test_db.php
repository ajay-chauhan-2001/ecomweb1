<?php
require_once 'config.php';
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test database connection
    $pdo = $GLOBALS['pdo'];
    echo "<p style='color:green'>✓ Database connection successful!</p>";
    
    // Create necessary tables
    echo "<h3>Creating Tables...</h3>";
    
    // Products table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `description` text,
            `price` decimal(10,2) NOT NULL,
            `sale_price` decimal(10,2) DEFAULT NULL,
            `stock` int(11) NOT NULL DEFAULT 0,
            `image` varchar(255) DEFAULT NULL,
            `category_id` int(11) DEFAULT NULL,
            `status` enum('active','inactive') NOT NULL DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `category_id` (`category_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "<p style='color:green'>✓ Products table created/checked</p>";
    
    // Categories table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `status` enum('active','inactive') NOT NULL DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "<p style='color:green'>✓ Categories table created/checked</p>";
    
    // Cart table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `cart` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `session_id` varchar(255) DEFAULT NULL,
            `product_id` int(11) NOT NULL,
            `quantity` int(11) NOT NULL DEFAULT 1,
            `price` decimal(10,2) NOT NULL,
            `sale_price` decimal(10,2) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            KEY `session_id` (`session_id`),
            KEY `product_id` (`product_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "<p style='color:green'>✓ Cart table created/checked</p>";
    
    // Insert sample data
    echo "<h3>Inserting Sample Data...</h3>";
    
    // Insert sample categories
    $categories = [
        ['name' => 'Living Room', 'slug' => 'living-room'],
        ['name' => 'Bedroom', 'slug' => 'bedroom'],
        ['name' => 'Dining Room', 'slug' => 'dining-room']
    ];
    
    foreach ($categories as $category) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$category['name'], $category['slug']]);
    }
    echo "<p style='color:green'>✓ Sample categories inserted</p>";
    
    // Insert sample products
    $products = [
        [
            'name' => 'Modern Sofa',
            'description' => 'Comfortable modern sofa for your living room',
            'price' => 299.99,
            'sale_price' => 249.99,
            'stock' => 10,
            'category_id' => 1
        ],
        [
            'name' => 'Queen Size Bed',
            'description' => 'Luxurious queen size bed with storage',
            'price' => 499.99,
            'sale_price' => null,
            'stock' => 5,
            'category_id' => 2
        ]
    ];
    
    foreach ($products as $product) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO products 
            (name, description, price, sale_price, stock, category_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price'],
            $product['sale_price'],
            $product['stock'],
            $product['category_id']
        ]);
    }
    echo "<p style='color:green'>✓ Sample products inserted</p>";
    
    echo "<h3 style='color:green'>✓ All operations completed successfully!</h3>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Error Code: " . $e->getCode() . "</p>";
    echo "<p>Error File: " . $e->getFile() . "</p>";
    echo "<p>Error Line: " . $e->getLine() . "</p>";
}
?> 
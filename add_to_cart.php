<?php
session_start();
require_once 'includes/functions.php';
require_once 'config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'cart_count' => 0
];

try {
    // Check if it's an AJAX request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get product ID and quantity
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Validate input
        if ($productId <= 0) {
            $response['message'] = 'Invalid product ID';
            echo json_encode($response);
            exit;
        }

        if ($quantity <= 0) {
            $response['message'] = 'Invalid quantity';
            echo json_encode($response);
            exit;
        }

        // Add to cart
        $result = addToCart($productId, $quantity);
        
        if ($result['success']) {
            $response['success'] = true;
            $response['message'] = $result['message'];
            $response['cart_count'] = getCartCount();
        } else {
            $response['message'] = $result['message'];
        }
    } else {
        $response['message'] = 'Invalid request method';
    }
} catch (Exception $e) {
    error_log("Add to Cart Error: " . $e->getMessage());
    $response['message'] = 'An error occurred. Please try again.';
}

// Return JSON response
echo json_encode($response);
exit; 
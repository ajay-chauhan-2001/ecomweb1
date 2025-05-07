<?php
session_start();

// Initialize the cart if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($productId > 0) {
        // If the product is already in cart, increase quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            // Otherwise, add new product with quantity 1
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'quantity' => 1
            ];
        }

        // If request is AJAX (from jQuery), return JSON response
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode([
                'status' => 'success',
                'message' => 'Product added to cart',
                'cart_count' => array_sum(array_column($_SESSION['cart'], 'quantity'))
            ]);
            exit;
        } else {
            // If not AJAX, redirect back
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        // Invalid product id
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product ID'
        ]);
        exit;
    }
}

// (Optional) Handle remove or update cart here if you want
?>

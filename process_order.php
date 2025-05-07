<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zip = $_POST['zip'] ?? '';

// Get cart items
$cartItems = getCartItems();
$total = calculateCartTotal();

// Create order
$orderId = createOrder([
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'city' => $city,
    'state' => $state,
    'zip' => $zip,
    'total' => $total,
    'items' => $cartItems['items']
]);

if ($orderId) {
    // Clear cart
    clearCart();
    
    // Redirect to success page
    header('Location: order_success.php?id=' . $orderId);
    exit;
} else {
    // Redirect back to checkout with error
    header('Location: checkout.php?error=1');
    exit;
} 
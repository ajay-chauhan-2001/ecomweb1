<?php

require_once 'config/database.php';
require_once 'includes/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clean cart
cleanInvalidCartProducts();

$session_id = getSessionId();
$cartItems = getCartItems($session_id);
$total = calculateCartTotal($cartItems);

// Form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($cartItems['items'])) {
        $_SESSION['error'] = 'Your cart is empty. Please add products before placing an order.';
        header("Location: checkout.php");
        exit;
    }

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $payment_status = 'pending';

    $errors = [];

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($phone)) $errors[] = 'Phone is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';
    if (empty($state)) $errors[] = 'State is required';
    if (empty($zip_code)) $errors[] = 'ZIP code is required';

    if (empty($errors)) {
        $conn = getDBConnection();
        try {
            $conn->begin_transaction();

            // Check if user exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                // Insert guest user
                $stmt = $conn->prepare("INSERT INTO users (name, email, phone, address, city, state, zip_code, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'guest')");
                $stmt->bind_param('sssssss', $name, $email, $phone, $address, $city, $state, $zip_code);
                $stmt->execute();
                $user_id = $stmt->insert_id;
            } else {
                $user_id = $user['id'];
            }

            // Create order
            $order_number = uniqid('ORD-');
            $created_at = date('Y-m-d H:i:s');
            $status = 'pending';
            $billing_address = $address;
            $notes = '';

            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, order_number, total_amount, status, payment_status, shipping_address, billing_address, notes, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('isdsssssss', $user_id, $order_number, $total, $status, $payment_status, $address, $billing_address, $notes, $created_at, $created_at);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // Insert order items
            if (!empty($cartItems['items'])) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($cartItems['items'] as $item) {
                    $checkStmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
                    $checkStmt->bind_param('i', $item['id']);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    if ($checkResult->num_rows > 0) {
                        $stmt->bind_param('iiid', $order_id, $item['id'], $item['quantity'], $item['price']);
                        $stmt->execute();
                    }
                }
            }

            // Clear cart
            if (isset($_SESSION['user_id'])) {
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $stmt->bind_param('i', $_SESSION['user_id']);
                $stmt->execute();
            } else {
                $session_id = session_id();
                $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
                $stmt->bind_param('s', $session_id);
                $stmt->execute();
            }

            $conn->commit();
            clearCart();

            header("Location: order-confirmation.php?id=$order_id");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = 'Error processing order: ' . $e->getMessage();
        }
    }
}

require_once 'includes/header.php';
$pageTitle = 'Checkout';

?>

<!-- Checkout Section -->
<section class="checkout py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">

                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                    echo htmlspecialchars($_SESSION['error']);
                                    unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="checkoutForm" action="checkout.php" method="post">
                            <input type="hidden" id="cartEmpty" value="<?php echo empty($cartItems['items']) ? '1' : '0'; ?>">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10}" maxlength="10" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" required value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="zip_code" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" required value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock"></i> Place Order
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $subtotal = 0;
                        $totalDiscount = 0;
                        $finalTotal = 0;
                        ?>

                        <?php if (!empty($cartItems['items'])): ?>
                            <?php foreach ($cartItems['items'] as $item):
                                $mainPrice = (float)$item['price'];
                                $salePrice = isset($item['sale_price']) ? (float)$item['sale_price'] : $mainPrice;
                                $quantity = (int)$item['quantity'];

                                $itemSubtotal = $mainPrice * $quantity;
                                $itemFinalPrice = $salePrice * $quantity;
                                $itemDiscount = $itemSubtotal - $itemFinalPrice;

                                $subtotal += $itemSubtotal;
                                $totalDiscount += $itemDiscount;
                                $finalTotal += $itemFinalPrice;
                            endforeach; ?>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹<?= number_format($subtotal, 2) ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span>- ₹<?= number_format($totalDiscount, 2) ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span class="text-primary" id="totalAmountFinal">₹<?= number_format($finalTotal, 2) ?></span>
                            </div>

                            <a href="shop.php" class="btn btn-outline-secondary w-100 mt-4">Continue Shopping</a>

                        <?php else: ?>
                            <p>Your cart is empty.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $("form").validate({
        rules: {
            name: "required",
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },
            address: "required",
            city: "required",
            state: "required",
            zip_code: "required"
        },
        messages: {
            name: "Please enter your name",
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be exactly 10 digits",
                maxlength: "Phone number must be exactly 10 digits",
                digits: "Phone number can only contain numbers"
            },
            address: "Please enter your address",
            city: "Please enter your city",
            state: "Please enter your state",
            zip_code: "Please enter your ZIP code"
        }
    });

    $('#checkoutForm').on('submit', function(e) {
        var cartIsEmpty = $('#cartEmpty').val();
        if (cartIsEmpty == '1') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Your cart is empty! Please add products before placing an order.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Shop Now'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'shop.php'; // Redirect to shop
                }
            });
        }
    });
});

</script>

<style>
.error {
    color: red;
}
</style>

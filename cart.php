<?php
session_start();
require_once 'config/database.php'; // DB connection

// User / Guest detection
if (isset($_SESSION['user_id'])) {
    $userId = (int)$_SESSION['user_id'];
    $sessionId = null;
} else {
    if (!isset($_SESSION['guest_id'])) {
        $randomNumber = random_int(10000, 99999);
        $datePart = date('Ymd');
        $_SESSION['guest_id'] = 'guest_' . $datePart . '_' . $randomNumber;
    }
    $sessionId = $_SESSION['guest_id'];
    $userId = null;
}

// Fetch cart items
$query = "
    SELECT c.*, p.name, p.image, p.stock, p.price, p.sale_price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE " . ($userId !== null ? "c.user_id = ?" : "c.session_id = ?");

$stmt = $conn->prepare($query);

if ($userId !== null) {
    $stmt->bind_param('i', $userId);
} else {
    $stmt->bind_param('s', $sessionId);
}

$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$subTotalBeforeDiscount = 0;
$totalDiscount = 0;
$finalTotal = 0;

while ($row = $result->fetch_assoc()) {
    $price = $row['price'];
    $salePrice = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
    $quantity = $row['quantity'];

    $subtotal = $salePrice * $quantity;

    $cartItems[] = [
        'id' => $row['id'],
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $price,
        'sale_price' => $row['sale_price'],
        'quantity' => $quantity,
        'stock' => $row['stock'],
        'image' => $row['image'],
        'subtotal' => $subtotal
    ];

    $subTotalBeforeDiscount += $price * $quantity;
    $totalDiscount += ($price - $salePrice) * $quantity;
    $finalTotal += $salePrice * $quantity;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        .cart { background: #f8f9fa; padding: 50px 0; }
        img.product-image { width: 80px; height: 80px; object-fit: cover; }
        .quantity-buttons button { width: 30px; height: 30px; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="cart">
    <div class="container">
        <h1 class="mb-4">Shopping Cart</h1>

        <?php if (empty($cartItems)): ?>
            <div class="text-center my-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <a href="shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr id="cart-item-<?= $item['product_id'] ?>">
                                        <td class="d-flex align-items-center">
                                            <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="me-3 product-image">
                                            <div>
                                                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                                <small class="text-muted">SKU: <?= htmlspecialchars($item['product_id']) ?></small>
                                            </div>
                                        </td>
                                        <td>₹<?= number_format($item['sale_price'] > 0 ? $item['sale_price'] : $item['price'], 2) ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center quantity-buttons">
                                                <button class="btn btn-outline-secondary decrement" data-product-id="<?= $item['product_id'] ?>"><i class="fas fa-minus"></i></button>
                                                <input type="text" class="form-control quantity-input mx-1 text-center" style="width: 50px;" data-product-id="<?= $item['product_id'] ?>" value="<?= $item['quantity'] ?>" readonly>
                                                <button class="btn btn-outline-secondary increment" data-product-id="<?= $item['product_id'] ?>"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </td>
                                        <td class="subtotal-amount text-center" data-product-id="<?= $item['product_id'] ?>">₹<?= number_format($item['subtotal'], 2) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm remove-item" data-product-id="<?= $item['product_id'] ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="orderSummarySubtotal">₹<?= number_format($subTotalBeforeDiscount, 2) ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span id="orderSummaryDiscount">₹<?= number_format($totalDiscount, 2) ?></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="orderSummaryFinalTotal" class="text-primary">₹<?= number_format($finalTotal, 2) ?></span>
                            </div>

                            <a href="checkout.php" class="btn btn-primary w-100 mt-4">Proceed to Checkout</a>
                            <a href="shop.php" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Cart AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const updateQuantity = (productId, action) => {
        fetch('ajax/update_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const qtyInput = document.querySelector(`input.quantity-input[data-product-id="${productId}"]`);
                const subtotalTd = document.querySelector(`td.subtotal-amount[data-product-id="${productId}"]`);

                animateValueChange(qtyInput, data.new_quantity);
                animateValueChange(subtotalTd, '₹' + parseFloat(data.new_subtotal).toFixed(2));

                document.getElementById('orderSummarySubtotal').textContent = '₹' + parseFloat(data.order_summary.subtotal).toFixed(2);
                document.getElementById('orderSummaryDiscount').textContent = '₹' + parseFloat(data.order_summary.discount).toFixed(2);
                document.getElementById('orderSummaryFinalTotal').textContent = '₹' + parseFloat(data.order_summary.total).toFixed(2);

                updateCartCount();
            } else {
                alert(data.message || 'Failed to update cart.');
            }
        })
        .catch(error => console.error('Error:', error));
    };

    const removeItem = (productId) => {
        fetch('ajax/remove_from_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`cart-item-${productId}`);
                if (row) {
                    row.style.transition = 'opacity 0.5s';
                    row.style.opacity = 0;
                    setTimeout(() => row.remove(), 500);
                }
                document.getElementById('orderSummarySubtotal').textContent = '₹' + parseFloat(data.order_summary.subtotal).toFixed(2);
                document.getElementById('orderSummaryDiscount').textContent = '₹' + parseFloat(data.order_summary.discount).toFixed(2);
                document.getElementById('orderSummaryFinalTotal').textContent = '₹' + parseFloat(data.order_summary.total).toFixed(2);

                updateCartCount();
            } else {
                alert('Failed to remove item.');
            }
        });
    };

    const animateValueChange = (element, newValue) => {
        element.style.opacity = 0;
        setTimeout(() => {
            if (element.tagName.toLowerCase() === 'input') {
                element.value = newValue;
            } else {
                element.textContent = newValue;
            }
            element.style.opacity = 1;
        }, 300);
    };

    function updateCartCount() {
        fetch('ajax/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count;

                    if (data.count == 0) {
                        cartCountElement.style.display = 'none';
                    } else {
                        cartCountElement.style.display = 'inline-block';
                    }
                }
            }
        })
        .catch(error => console.error('Error fetching cart count:', error));
    }

    document.querySelectorAll('.increment').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            updateQuantity(productId, 'increase');
        });
    });

    document.querySelectorAll('.decrement').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            updateQuantity(productId, 'decrease');
        });
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const productId = btn.dataset.productId;
            if (confirm('Are you sure you want to remove this item?')) {
                removeItem(productId);
            }
        });
    });

});
</script>

</body>
</html>

<?php
require_once 'includes/header.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
$product = getProductById($productId);

if (!$product) {
    header('Location: index.php');
    exit;
}

$pageTitle = $product['name'];
?>

<section class="product-details py-5">
    <div class="container">
        <div class="text-start mb-4">
            <a href="shop.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Shop
            </a>
        </div>

        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-gallery">
                    <div class="main-image mb-3">
                        <?php if (!empty($product['image'])): ?>
                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                                 class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 style="max-height: 500px; object-fit: cover;">
                        <?php else: ?>
                            <img src="assets/images/products/default.jpg"
                                 class="img-fluid" alt="No image available"
                                 style="max-height: 500px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- Price -->
                <div class="price mb-3">
                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                        <span class="h4 text-dark">₹<?php echo number_format($product['sale_price'], 2); ?></span>
                        <span class="text-danger text-decoration-line-through ms-2">
                            ₹<?php echo number_format($product['price'], 2); ?>
                        </span>
                    <?php else: ?>
                        <span class="h4 text-dark">₹<?php echo number_format($product['price'], 2); ?></span>
                    <?php endif; ?>
                </div>

                <h5 class="mb-3"><?php echo htmlspecialchars($product['description']); ?></h5>

                <!-- Add to Cart Form -->
                <form action="" method="POST" class="add-to-cart-form" novalidate>
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">

                    <div class="form-group mb-4">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary qtyminus">-</button>
                            <input type="number" class="form-control text-center qty" id="quantity" name="quantity"
                                   value="1" min="1" max="<?php echo (int)$product['stock']; ?>">
                            <button type="button" class="btn btn-outline-secondary qtyplus">+</button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>

                        <a href="checkout.php" id="buy-now-btn" class="btn btn-primary">
                            <i class="fas fa-bolt me-2"></i>Buy Now
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- jQuery and SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function updateCartCount() {
    $.ajax({
        url: 'ajax/get_cart_count.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#cart-count').text(response.count);
            }
        }
    });
}

function addToCart(productId, quantity = 1) {
    $.ajax({
        url: 'ajax/add_to_cart.php',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: response.message,
                    confirmButtonColor: '#3085d6'
                });
                updateCartCount();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    confirmButtonColor: '#d33'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong!',
                confirmButtonColor: '#d33'
            });
        }
    });
}

$(document).ready(function() {
    // Handle Add to Cart form submit
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        var productId = $(this).find('input[name="product_id"]').val();
        var quantity = $(this).find('input[name="quantity"]').val();
        addToCart(productId, quantity);
    });

    // Increase quantity
    $('.qtyplus').click(function() {
        var $input = $(this).siblings('.qty');
        var val = parseInt($input.val());
        var max = parseInt($input.attr('max'));
        if (val < max) {
            $input.val(val + 1);
        }
    });

    // Decrease quantity
    $('.qtyminus').click(function() {
        var $input = $(this).siblings('.qty');
        var val = parseInt($input.val());
        if (val > 1) {
            $input.val(val - 1);
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>

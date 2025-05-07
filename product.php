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

                <!--  -->

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

                        <button type="submit" id="buy-now-btn" class="btn btn-primary">
                            <i class="fas fa-bolt me-2"></i>Buy Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $(".qtyminus").click(function () {
        var qty = parseInt($("#quantity").val()) || 1;
        if (qty > 1) {
            $("#quantity").val(qty - 1);
        }
    });

    $(".qtyplus").click(function () {
        var qty = parseInt($("#quantity").val()) || 1;
        var max = parseInt($("#quantity").attr('max')) || 999;
        if (qty < max) {
            $("#quantity").val(qty + 1);
        }
    });

    $("#quantity").on('input', function() {
        var val = parseInt($(this).val()) || 1;
        if (val < 1) val = 1;
        var max = parseInt($(this).attr('max')) || 999;
        if (val > max) val = max;
        $(this).val(val);
    });

    let isBuyNow = false;
    $('#buy-now-btn').click(function() {
        isBuyNow = true;
    });

    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        $form.find('button').prop('disabled', true);

        const productId = $('input[name="product_id"]').val();
        const quantity = $('input[name="quantity"]').val();

        $.ajax({
            url: 'ajax/add_to_cart.php',
            type: 'POST',
            dataType: 'json', // Expect JSON response
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.status === 'success') {
                    updateCartCount();
                    if (isBuyNow) {
                        window.location.href = 'cart.php';
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Product added successfully.'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add product.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
            },
            complete: function() {
                $form.find('button').prop('disabled', false);
            }
        });
    });

    function updateCartCount() {
        $.ajax({
            url: 'ajax/get_cart_count.php',
            type: 'GET',
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    $('.cart-count').text(response.count);
                }
            }
        });
    }

    updateCartCount();
});
</script>

<?php require_once 'includes/footer.php'; ?>

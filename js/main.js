// ... existing code ...

function updateCartCount() {
    $.ajax({
        url: 'ajax/get_cart_count.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('.cart-count').text(response.count);
            }
        }
    });
}

// Update cart count when page loads
$(document).ready(function() {
    updateCartCount();
});

// Add to cart button click handler
$(document).on('click', '.add-to-cart', function(e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    var quantity = $(this).closest('.add-to-cart-form').find('input[name="quantity"]').val();
    
    $.ajax({
        url: 'ajax/add_to_cart.php',
        type: 'POST',
        data: { 
            product_id: productId,
            quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateCartCount();
                alert('Product added to cart successfully!');
            } else {
                alert('Error adding product to cart: ' + response.message);
            }
        }
    });
});

// Buy Now button click handler
$(document).on('click', '.buy-now', function(e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    var quantity = $(this).closest('.add-to-cart-form').find('input[name="quantity"]').val();
    
    $.ajax({
        url: 'ajax/add_to_cart.php',
        type: 'POST',
        data: { 
            product_id: productId,
            quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Redirect to checkout page
                window.location.href = 'checkout.php';
            } else {
                alert('Error adding product to cart: ' + response.message);
            }
        }
    });
});

// Update quantity
$(document).on('click', '.update-quantity', function(e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    var action = $(this).data('action');
    var input = $(this).siblings('.quantity-input');
    var currentQuantity = parseInt(input.val());
    var newQuantity = action === 'increase' ? currentQuantity + 1 : currentQuantity - 1;
    
    if (newQuantity < 1) return;
    
    $.ajax({
        url: 'ajax/update_cart.php',
        type: 'POST',
        data: {
            product_id: productId,
            quantity: newQuantity
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                input.val(newQuantity);
                updateCartCount();
                location.reload(); // Refresh to update totals
            } else {
                alert('Error updating quantity: ' + response.message);
            }
        }
    });
});

// Remove from cart
$(document).on('click', '.remove-from-cart', function(e) {
    e.preventDefault();
    var productId = $(this).data('product-id');
    
    if (confirm('Are you sure you want to remove this item?')) {
        $.ajax({
            url: 'ajax/remove_from_cart.php',
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateCartCount();
                    location.reload(); // Refresh to update cart
                } else {
                    alert('Error removing item: ' + response.message);
                }
            }
        });
    }
});

// ... existing code ...

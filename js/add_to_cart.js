$(document).ready(function() {
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const quantity = $(this).data('quantity') || 1;

        $.ajax({
            url: '/ecomweb/ajax/add_to_cart.php',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    if (response.cart_count !== undefined) {
                        $('.cart-count').text(response.cart_count);
                    } else {
                        updateCartCount();
                    }
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX error: ' + error);
            }
        });
    });

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
});

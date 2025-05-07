// Function to update cart count
function updateCartCount() {
    $.ajax({
        url: '../ajax/get_cart_count.php',
        type: 'GET',
        success: function(response) {
            const parts = response.trim().split('|');
            if (parts[0] === 'success') {
                $('.cart-count').text(parts[1]);
            } else {
                console.error('Error updating cart count');
            }
        },
        error: function() {
            console.error('Failed to update cart count.');
        }
    });
}

$(document).ready(function() {
    updateCartCount();
});

// Quantity control
$(".qtyminus").on("click", function(){
    var qty = parseInt($(".qty").val());
    if (qty > 1) $(".qty").val(qty - 1);
});

$(".qtyplus").on("click", function(){
    var qty = parseInt($(".qty").val());
    var max = parseInt($(".qty").attr('max'));
    if (qty < max) $(".qty").val(qty + 1);
});

// Add to Cart
$(document).ready(function() {
    let isBuyNow = false;

    $(document).on('click', '#buy-now-btn', function() {
        isBuyNow = true;
    });

    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const productId = form.find('input[name="product_id"]').val();
        const quantity = form.find('input[name="quantity"]').val();
        
        $.ajax({
            url: '../ajax/add_to_cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                const parts = response.trim().split('|');
                if (parts[0] === 'success') {
                    updateCartCount();

                    if (isBuyNow) {
                        window.location.href = '../cart.php';
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            text: parts[1],
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: parts[1],
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

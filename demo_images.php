<?php
$pageTitle = 'Product Images Demo';
require_once 'includes/header.php';

// Add custom CSS for demo page
echo '<link rel="stylesheet" href="assets/css/demo.css">';

// Get all product images from the directory
$imageDir = 'assets/images/products/';
$images = glob($imageDir . '*.jpg');
?>

<div class="container py-5">
    <h1 class="text-center mb-4">Product Images Demo</h1>
    
    <div class="row">
        <?php foreach ($images as $image): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo $image; ?>" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo basename($image); ?></h5>
                        <p class="card-text">Image path: <?php echo $image; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
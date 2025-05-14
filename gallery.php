<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get all product images
$images = getAllProductImages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Images Gallery - <?php echo $site_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
    <style>
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        .gallery-item .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover .overlay {
            transform: translateY(0);
        }
        .gallery-item .product-name {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .gallery-item .product-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffc107;
        }
        .gallery-item .view-details {
            display: block;
            margin-top: 10px;
            color: #fff;
            text-decoration: none;
        }
        .gallery-item .view-details:hover {
            color: #ffc107;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .category-filter {
            margin-bottom: 15px;
        }
        .image-count {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Product Images Gallery</h1>

        <!-- Gallery Grid -->
        <div class="row g-4" id="gallery-grid">
            <?php 
            $productImages = [];
            foreach ($images as $image) {
                if (!isset($productImages[$image['product_id']])) {
                    $productImages[$image['product_id']] = [];
                }
                $productImages[$image['product_id']][] = $image;
            }

            foreach ($productImages as $productId => $productImageGroup): 
                $mainImage = $productImageGroup[0];
                $imageCount = count($productImageGroup);
            ?>
                <div class="col-md-4 col-lg-3">
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars($mainImage['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($mainImage['product_name']); ?>">
                        <?php if ($imageCount > 1): ?>
                            <div class="image-count">
                                <i class="fas fa-images"></i> <?php echo $imageCount; ?> images
                            </div>
                        <?php endif; ?>
                        <div class="overlay">
                            <div class="product-name"><?php echo htmlspecialchars($mainImage['product_name']); ?></div>
                            <div class="product-price">₹<?php echo number_format($mainImage['price'], 2); ?></div>
                            <a href="product.php?id=<?php echo $productId; ?>" class="view-details">
                                View All Images <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <div class="text-center mt-5">
                <div class="alert alert-info">
                    No product images found.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

</body>
</html> 
<?php
$pageTitle = 'Shop';
require_once 'includes/header.php';

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 12;

// Get filtered products
$products = getFilteredProducts($category, $search, $sort, $page, $perPage);
$totalProducts = getTotalProducts($category, $search);
$totalPages = ceil($totalProducts / $perPage);

// Get all categories with count
$categories = getAllCategoriesWithCount();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .card { transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
    .image-wrapper { position: relative; overflow: hidden; }
    .image-wrapper img { transition: transform 0.3s ease; }
    .image-wrapper:hover img { transform: scale(1.05); }
    .hover-icons {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        display: flex; gap: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .image-wrapper:hover .hover-icons { opacity: 1; }
    .hover-icons .btn {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        border: none; border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .hover-icons .btn:hover {
        transform: scale(1.1);
        background: white;
    }
    .discount-badge {
        position: absolute;
        top: 10px; left: 10px;
        background: #dc3545; color: white;
        padding: 5px 10px; border-radius: 4px;
        font-weight: bold; z-index: 1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .price-container { display: flex; align-items: center; gap: 8px; }
    .original-price { text-decoration: line-through; color: #6c757d; font-size: 0.9rem; }
    .sale-price { color: #0e0c0c; font-weight: bold; font-size: 1.1rem; }
</style>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Categories -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>Categories</strong>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item <?php echo $category == '' ? 'active' : ''; ?>">
                        <a href="shop.php" class="text-decoration-none d-flex justify-content-between">
                            <span>All Categories</span>
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="list-group-item <?php echo $category == $cat['slug'] ? 'active' : ''; ?>">
    <a href="?category=<?php echo $cat['slug']; ?>" 
       class="text-decoration-none d-flex justify-content-between text-dark <?php echo $category == $cat['slug'] ? 'fw-bold text-primary' : ''; ?>">
        <span><?php echo htmlspecialchars($cat['name']); ?></span>
        <span class="badge bg-secondary"><?php echo $cat['product_count']; ?></span>
    </a>
</li>

                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Main Product Section -->
        <div class="col-md-9">
            <!-- Top Filter Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="" method="GET" class="row g-3">
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row">
                <?php if (empty($products)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">No products found matching your criteria.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="image-wrapper" style="height: 250px;">
                                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                        <div class="discount-badge">
                                            <?php 
                                            $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
                                            echo $discount . '% OFF';
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <img src="assets/images/products/<?php echo htmlspecialchars($product['image'] ?: 'default.jpg'); ?>" 
                                             class="card-img-top w-100" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             style="object-fit: cover;">
                                    </a>
                                    <div class="hover-icons">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn"><i class="fas fa-eye text-primary"></i></a>
                                        <a href="#" class="btn"><i class="fas fa-shopping-cart text-success"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="price-container mb-2">
                                        <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                            <span class="sale-price">₹<?php echo number_format($product['sale_price'], 2); ?></span>
                                            <span class="original-price">₹<?php echo number_format($product['price'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="sale-price">₹<?php echo number_format($product['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($product['name']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo $category; ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $category; ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo $category; ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

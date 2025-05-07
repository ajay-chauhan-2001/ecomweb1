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

// Get all categories for filter
$categories = getAllCategories();
?>

<!-- Optional CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .image-wrapper {
        position: relative;
        overflow: hidden;
    }
    .image-wrapper img {
        transition: transform 0.3s ease;
    }
    .image-wrapper:hover img {
        transform: scale(1.05);
    }
    .hover-icons {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .image-wrapper:hover .hover-icons {
        opacity: 1;
    }
    .hover-icons .btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    .hover-icons .btn:hover {
        transform: scale(1.1);
        background: white;
    }
    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
        z-index: 1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .price-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .sale-price {
        color: rgb(14, 12, 12);
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

<div class="container py-5">
    <!-- Top Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['slug']; ?>" <?php echo $category === $cat['slug'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="btn btn-info">Apply Filters</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php if (empty($products)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found matching your criteria.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-3 mb-4">
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
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        <a href="#" class="btn">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price-container">
                                            <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                                <span class="sale-price">₹<?php echo number_format($product['sale_price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="sale-price">₹<?php echo number_format($product['price'], 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <!-- <?php if ($totalPages > 1): ?> -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . $category : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

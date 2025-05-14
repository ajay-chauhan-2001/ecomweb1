<?php
// Fetch categories (using your existing function)
$categories = getAllCategories();
?>

<div class="sidebar" id="sidebar" data-aos="fade-right" data-aos-duration="800">
    <!-- <h2 class="sidebar-title text-center mb-4">Shop Categories</h2> -->
    <ul class="list-unstyled category-list">
        <?php foreach ($categories as $category): ?>
            <li class="category-item">
                <a href="category.php?id=<?php echo htmlspecialchars($category['id']); ?>" 
                   class="category-link d-flex align-items-center">
                    <i class="fas fa-chair me-2"></i>
                    <?php echo htmlspecialchars($category['name']); ?>
                    <span class="product-count ms-auto">
                        (<?php echo getProductCountByCategory($category['id']); ?>)
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
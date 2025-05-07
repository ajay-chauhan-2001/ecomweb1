<?php
session_start();
require_once '../includes/functions.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Validate product ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch product
$productQuery = "SELECT * FROM products WHERE id = $product_id";
$productResult = $conn->query($productQuery);
$product = $productResult->fetch_assoc();

if (!$product) {
    header('Location: products.php');
    exit;
}

// Fetch categories
$categories = [];
$catQuery = "SELECT * FROM categories ORDER BY name ASC";
$catResult = $conn->query($catQuery);
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if (isset($_POST['edit_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $status = $conn->real_escape_string($_POST['status']);

    $updateImage = '';
    if (!empty($_FILES['image']['name'])) {
        $newImage = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = '../assets/images/products/' . $newImage;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
        $updateImage = ", image = '$newImage'";
    }

    $updateQuery = "UPDATE products SET 
                    name = '$name', 
                    category_id = '$category_id', 
                    price = '$price', 
                    stock = '$stock', 
                    status = '$status'
                    $updateImage
                    WHERE id = $product_id";

    if ($conn->query($updateQuery)) {
        header('Location: products.php?message=Product updated successfully');
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}

$page_title = "Edit Product";
require_once 'includes/header.php';
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="col-md-10 main-content d-flex justify-content-center">
    <div class="card w-100" style="max-width: 700px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Edit Product</h4>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label><br>
                    <?php if (!empty($product['image'])): ?>
                        <img src="../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" width="100" class="mb-2"><br>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>

            <div class="card-footer text-center">
                <a href="products.php" class="btn btn-secondary btn-sm">Cancel</a>
                <button type="submit" name="edit_product" class="btn btn-success btn-sm">Update Product</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

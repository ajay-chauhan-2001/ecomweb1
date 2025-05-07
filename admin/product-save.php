<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();
$error = '';
$success = false;
$editing = false;
$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'sale_price' => '',
    'stock' => '',
    'category_id' => '',
    'status' => 'active',
    'image' => ''
];

// Fetch categories
$categories = [];
$catQuery = "SELECT * FROM categories ORDER BY name ASC";
$catResult = $conn->query($catQuery);
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// If editing
if (isset($_GET['id'])) {
    $editing = true;
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die('Product not found.');
    }
}

// Handle form submit
if (isset($_POST['save_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $sale_price = (float)$_POST['sale_price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $status = $_POST['status'];
    $imageName = $product['image']; // keep old if no new uploaded

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = 'product_' . time() . '.' . $ext;
        $uploadPath = '../assets/images/products/' . $imageName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $error = "Failed to upload image.";
        }
    }

    if ($error == '') {
        if ($editing) {
            // UPDATE
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, image=?, price=?, sale_price=?, stock=?, category_id=?, status=? WHERE id=?");
            $stmt->bind_param('sssddiisi', $name, $description, $imageName, $price, $sale_price, $stock, $category_id, $status, $id);
        } else {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO products (name, description, image, price, sale_price, stock, category_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssddiis', $name, $description, $imageName, $price, $sale_price, $stock, $category_id, $status);
        }

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

$page_title = $editing ? "Edit Product" : "Add Product";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="col-md-10 main-content d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="card-body">
            <h2 class="text-center mb-4"><?php echo $editing ? 'Edit Product' : 'Add New Product'; ?></h2>

            <form id="productForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" value="<?php echo htmlspecialchars($product['sale_price']); ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control">
                    <?php if ($product['image']): ?>
                        <img src="../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" class="mt-2" width="120">
                    <?php endif; ?>
                </div>

                <div class="card-footer text-end">
                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="save_product" class="btn btn-success"><?php echo $editing ? 'Update' : 'Add'; ?> Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    <?php if ($success): ?>
    Swal.fire({
        title: 'Success!',
        text: '<?php echo $editing ? "Product updated successfully." : "Product added successfully."; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'products.php';
    });
    <?php elseif (!empty($error)): ?>
    Swal.fire({
        title: 'Error!',
        text: '<?php echo htmlspecialchars($error); ?>',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
});
</script>

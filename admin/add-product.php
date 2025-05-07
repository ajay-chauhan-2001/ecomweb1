<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Fetch categories
$categories = [];
$catQuery = "SELECT * FROM categories ORDER BY name ASC";
$catResult = $conn->query($catQuery);
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$error = '';
$success = false;

// Handle form submit
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $status = $_POST['status'];
    $imageName = '';

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = '../assets/images/products/' . $imageName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $error = "Failed to upload image.";
        }
    }

    if ($error == '') {
        $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, stock, status, image, created_at) 
                                 VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param('sdiiss', $name, $category_id, $price, $stock, $status, $imageName);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$page_title = "Add Product";
require_once 'includes/header.php';
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="col-md-10 main-content d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 100%; max-width: 600px;">
        <div class="card-body">
            <h2 class="text-center mb-4">Add New Product</h2>

            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="card-footer text-end">
    <a href="products.php" class="btn btn-secondary">Cancel</a>
    <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
</div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- jQuery, Validation, SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#addProductForm').validate({
        rules: {
            name: { required: true },
            category_id: { required: true },
            price: { required: true, number: true },
            stock: { required: true, digits: true },
            status: { required: true }
        },
        messages: {
            name: { required: "Please enter product name" },
            category_id: { required: "Please select a category" },
            price: { required: "Please enter price", number: "Price must be a number" },
            stock: { required: "Please enter stock", digits: "Stock must be a whole number" },
            status: { required: "Please select status" }
        },
        errorClass: "text-danger",
        errorElement: "div",
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });

    <?php if ($success): ?>
    Swal.fire({
        title: 'Success!',
        text: 'Product added successfully.',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'products.php';
        }
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

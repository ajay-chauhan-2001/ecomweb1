<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Get Category ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: categories.php?message=Invalid Category ID');
    exit;
}

$category_id = intval($_GET['id']);

// Fetch category details
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: categories.php?message=Category not found');
    exit;
}

$category = $result->fetch_assoc();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $status = $_POST['status'];
    $image_name = $category['image']; // default old image

    if ($name != '') {
        // Check if a new image was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('cat_', true) . '.' . $extension;
                $upload_path = '../assets/images/categories/' . $new_filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if (!empty($category['image']) && file_exists('../assets/images/categories/' . $category['image'])) {
                        unlink('../assets/images/categories/' . $category['image']);
                    }
                    $image_name = $new_filename;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Invalid image file type.";
            }
        }

        if (empty($error)) {
            $update_stmt = $conn->prepare("UPDATE categories SET name = ?, status = ?, image = ?, updated_at = NOW() WHERE id = ?");
            $update_stmt->bind_param('sssi', $name, $status, $image_name, $category_id);

            if ($update_stmt->execute()) {
                $success = true;
                // Refresh category data
                $category['name'] = $name;
                $category['status'] = $status;
                $category['image'] = $image_name;
            } else {
                $error = "Failed to update category.";
            }
        }
    } else {
        $error = "Category name is required.";
    }
}

$page_title = "Edit Category";
require_once 'includes/header.php';
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="col-md-10 main-content d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h2 class="text-center mb-4">Edit Category</h2>

            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="active" <?php echo $category['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $category['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category Image</label><br>
                    <?php if (!empty($category['image'])): ?>
                        <img src="../assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>" alt="Category Image" class="img-thumbnail mb-2" style="max-width: 150px;">
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="categories.php" class="btn btn-secondary mt-2">Cancel</a>
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
    $('#editCategoryForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            status: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter category name",
                minlength: "Category name must be at least 2 characters"
            },
            status: {
                required: "Please select a status"
            }
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
        text: 'Category updated successfully.',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'categories.php';
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

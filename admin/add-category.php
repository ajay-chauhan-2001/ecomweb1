<?php
session_start();
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $status = $_POST['status'];

    if ($name != '' && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];

        // Allowed image types
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($image['type'], $allowed_types)) {
            // Extract extension
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);

            // Create new image name: category_TIMESTAMP.ext
            $image_name = 'category_' . time() . '.' . $ext;
            $target_directory = '../assets/images/categories/' . $image_name;

            if (move_uploaded_file($image['tmp_name'], $target_directory)) {
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO categories (name, image, status, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param('sss', $name, $image_name, $status);
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error = "Database error. Please try again.";
                }
            } else {
                $error = "Failed to upload image. Please try again.";
            }
        } else {
            $error = "Invalid image type. Only JPG, PNG, and WEBP allowed.";
        }
    } else {
        $error = "All fields including image are required.";
    }
}

$page_title = "Add Category";
require_once 'includes/header.php';
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="col-md-10 main-content d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h2 class="text-center mb-4">Add New Category</h2>

            <form id="addCategoryForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Add Category</button>
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
    $('#addCategoryForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            status: {
                required: true
            },
            image: {
                required: true,
                extension: "jpg|jpeg|png|webp"
            }
        },
        messages: {
            name: {
                required: "Please enter category name",
                minlength: "Category name must be at least 2 characters"
            },
            status: {
                required: "Please select a status"
            },
            image: {
                required: "Please upload an image",
                extension: "Only JPG, JPEG, PNG, or WEBP files are allowed"
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
        text: 'Category added successfully.',
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

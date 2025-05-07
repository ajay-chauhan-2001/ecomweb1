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

    if ($name != '') {
        $stmt = $conn->prepare("INSERT INTO categories (name, status, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('ss', $name, $status);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "Category name is required.";
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

            <form id="addCategoryForm" method="POST">
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

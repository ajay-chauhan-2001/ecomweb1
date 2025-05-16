<?php
// session_start();

$pageTitle = 'Profile';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$user = getCurrentUser();
global $conn;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $newPhone = trim($_POST['phone']);
    $newAddress = trim($_POST['address']);
    $image = $_FILES['image'];

    if (empty($newName)) {
        $_SESSION['error'] = "Name is required.";
    } else {
        // Update user data
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $newName, $newPhone, $newAddress, $user['id']);

        if ($stmt->execute()) {
            $_SESSION['user_name'] = $newName;

            // Handle image upload
            if ($image['error'] === 0 && is_uploaded_file($image['tmp_name'])) {
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $filename = "assets/images/avatar/user_" . $user['id'] . "." . $ext;
                move_uploaded_file($image['tmp_name'], $filename);
                $conn->query("UPDATE users SET image = '$filename' WHERE id = " . $user['id']);
            }

            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }

        header('Location: profile.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile - FurniCraft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }
    .form-input {
      padding-left: 40px;
    }
    .image-preview {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #dee2e6;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
    <h3 class="text-center mb-4">Edit Your Profile</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="text-center">
        <img id="imagePreview" src="<?php echo isset($user['image']) && file_exists($user['image']) ? $user['image'] : 'assets/images/image.jpg'; ?>" alt="image" class="image-preview">
        <div class="mb-3">
          <input type="file" name="image" accept="image/*" class="form-control" onchange="previewimage(this)">
        </div>
      </div>

      <div class="mb-3 position-relative">
        <i class="bi bi-person-fill form-icon"></i>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control form-input" placeholder="Full Name" required>
      </div>

      <div class="mb-3 position-relative">
        <i class="bi bi-telephone-fill form-icon"></i>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="form-control form-input" placeholder="Phone Number">
      </div>

      <div class="mb-3 position-relative">
        <i class="bi bi-geo-alt-fill form-icon"></i>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" class="form-control form-input" placeholder="Address">
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function previewimage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('imagePreview').src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '<?php echo addslashes($_SESSION['success']); ?>'
    });
    <?php unset($_SESSION['success']); ?>
  <?php elseif (isset($_SESSION['error'])): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '<?php echo addslashes($_SESSION['error']); ?>'
    });
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
</script>

</body>
</html>

<?php include 'includes/footer.php'; ?>

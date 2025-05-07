<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getCurrentUser();

global $conn;

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    $newPassword = trim($_POST['password']); // optional

    if (empty($newName) || empty($newEmail)) {
        $error = "Name and Email are required.";
    } else {
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            // Update query
            if (!empty($newPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt->bind_param("sssi", $newName, $newEmail, $hashedPassword, $user['id']);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $newName, $newEmail, $user['id']);
            }

            if ($stmt->execute()) {
                $_SESSION['user_name'] = $newName;
                $_SESSION['user_email'] = $newEmail;

                $_SESSION['success'] = "Profile updated successfully.";
                redirect('profile.php');
            } else {
                $error = "Failed to update profile.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - FurniCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4">Edit Profile</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password <small>(leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

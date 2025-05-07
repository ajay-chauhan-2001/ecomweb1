<?php
require_once 'config/database.php'; // Make sure this returns a MySQLi connection

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } else {
        $conn = getDBConnection();

        if (!$conn) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Generate a simple token
                $token = bin2hex(random_bytes(32));
                $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hour validity

                // Store token and expiry in a 'password_resets' table or in your 'users' table
                $stmt_update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
                if ($stmt_update) {
                    $stmt_update->bind_param('sss', $token, $token_expiry, $email);
                    $stmt_update->execute();

                    // In a real app, you would send an email here
                    $resetLink = "http://yourdomain.com/reset-password.php?token=" . urlencode($token);
                    
                    // For now just display it
                    $success = "Password reset link: <a href='$resetLink'>$resetLink</a>";

                    $stmt_update->close();
                } else {
                    $errors[] = 'Database error: ' . $conn->error;
                }
            } else {
                $errors[] = 'No account found with that email address.';
            }

            $stmt->close();
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center">Forgot Password</h3>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="forgot-password.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Registered Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>

            <div class="text-center mt-3">
                <p>Remembered your password? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $profileImage = $_FILES['image'] ?? null;
    $profileImageName = 'default.png'; // Default image

    $errors = [];

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match';

    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Phone number must be exactly 10 digits';
    }

    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';
    if (empty($state)) $errors[] = 'State is required';
    if (empty($zip_code)) $errors[] = 'ZIP code is required';

    if (empty($errors)) {
        $conn = getDBConnection();
        if (!$conn) die('DB connection failed: ' . mysqli_connect_error());

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = 'Email already registered';
            } else {
                // ✅ Handle image upload
                if ($profileImage && $profileImage['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'assets/images/avatar/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                    $ext = strtolower(pathinfo($profileImage['name'], PATHINFO_EXTENSION));
                    $customFileName = 'user_' . time() . '.' . $ext;
                    $uploadPath = $uploadDir . $customFileName;

                    if (move_uploaded_file($profileImage['tmp_name'], $uploadPath)) {
                        $profileImageName = $customFileName;
                    } else {
                        $errors[] = 'Image upload failed. Using default.';
                    }
                }

                if (empty($errors)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt_insert = $conn->prepare("INSERT INTO users 
                        (name, email, password, phone, address, city, state, zip_code, image, role, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'user', 'active')");
                    if ($stmt_insert) {
                        $stmt_insert->bind_param(
                            'sssssssss',
                            $name,
                            $email,
                            $hashedPassword,
                            $phone,
                            $address,
                            $city,
                            $state,
                            $zip_code,
                            $profileImageName
                        );

                        if ($stmt_insert->execute()) {
                            header('Location: login.php?registered=1');
                            exit;
                        } else {
                            $errors[] = 'Registration failed. Try again.';
                        }
                        $stmt_insert->close();
                    } else {
                        $errors[] = 'Insert query error: ' . $conn->error;
                    }
                }
            }
            $stmt->close();
        } else {
            $errors[] = 'Query error: ' . $conn->error;
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
    <title>Register - FurniCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png">
    <style>
        .error { color: red; font-size: 0.875em; margin-top: 0.25rem; }
        .form-control.error { border-color: red; }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header"><h3 class="text-center">Register</h3></div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
                    <?php endif; ?>

                    <form id="registerForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" maxlength="10" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" required value="<?= htmlspecialchars($_POST['state'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip_code" class="form-control" required value="<?= htmlspecialchars($_POST['zip_code'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4">Register</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $("#registerForm").validate({
        rules: {
            name: { required: true, minlength: 2 },
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            confirm_password: { required: true, equalTo: "#password" },
            phone: { required: true, minlength: 10, maxlength: 10, digits: true },
            address: { required: true },
            city: { required: true },
            state: { required: true },
            zip_code: { required: true, minlength: 5 }
        },
        messages: {
            confirm_password: { equalTo: "Passwords do not match" }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("error").insertAfter(element);
        }
    });
</script>
</body>
</html>

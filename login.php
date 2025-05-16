<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors[] = 'Email and Password are required.';
    } else {
        $conn = getDBConnection();

        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? AND status = 'active'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['name']) . '!';
                redirect('index.php');
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FurniCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ✅ Favicon: Use Absolute URL -->
 <link rel="icon" type="image/x-icon" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-180x180.png">
    <link rel="icon" sizes="192x192" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-192x192.png">

</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (!empty($errors)): ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            html: '<?php echo implode("<br>", array_map("htmlspecialchars", $errors)); ?>'
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="card">
                <div class="card-header  text-dark">
                    <h4 class="mb-0"align="center">Login</h4>
                </div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="login.php" novalidate>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" required id="passwordInput">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4">Login</button>
                            <!-- <a href="index.php" class="btn btn-outline-secondary px-4">Cancel</a> -->
                        </div>

                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>
                        <a href="forgot-password.php">Forgot Password?</a> |
                        <a href="register.php">Create an Account</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Scripts: jQuery, Validation, and Toggle Password -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
    // jQuery Validation
    $(document).ready(function () {
        $('#loginForm').validate({
    rules: {
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 5
        }
    },
    messages: {
        email: "Please enter a valid email",
        password: {
            required: "Please enter your password",
            minlength: "Password must be at least 5 characters"
        }
    },
    errorClass: "text-danger mb-2",
    errorElement: "div",
    highlight: function (element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
        $(element).removeClass("is-invalid");
    },
    errorPlacement: function (error, element) {
        if (element.closest('.input-group').length) {
            error.insertAfter(element.closest('.input-group'));
        } else {
            error.insertAfter(element);
        }
    }
});


        // Password Toggle
        $('#togglePassword').on('click', function () {
            const input = $('#passwordInput');
            const icon = $(this).find('i');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>

</body>
</html>

<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Handle settings update
if (isset($_POST['update_settings'])) {
    $site_name = $_POST['site_name'];
    $site_email = $_POST['site_email'];
    $site_phone = $_POST['site_phone'];
    $site_address = $_POST['site_address'];
    $currency = $_POST['currency'];
    $tax_rate = $_POST['tax_rate'];
    
    $stmt = $conn->prepare("UPDATE settings SET 
                          site_name = ?, 
                          site_email = ?, 
                          site_phone = ?, 
                          site_address = ?, 
                          currency = ?, 
                          tax_rate = ? 
                          WHERE id = 1");
    $stmt->execute([$site_name, $site_email, $site_phone, $site_address, $currency, $tax_rate]);
    
    header('Location: settings.php?message=Settings updated successfully');
    exit;
}

// Get current settings
$stmt = $conn->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - FurniCraft Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a.active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-white text-center mb-4">FurniCraft Admin</h4>
                <a href="index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="products.php">
                    <i class="fas fa-box"></i> Products
                </a>
                <a href="categories.php">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="orders.php">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
                <a href="users.php">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="settings.php" class="active">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="logout.php" class="mt-4">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Settings</h2>
                </div>

                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" 
                                           value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="site_email" class="form-label">Site Email</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" 
                                           value="<?php echo htmlspecialchars($settings['site_email']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="site_phone" class="form-label">Site Phone</label>
                                    <input type="text" class="form-control" id="site_phone" name="site_phone" 
                                           value="<?php echo htmlspecialchars($settings['site_phone']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-select" id="currency" name="currency" required>
                                        <option value="USD" <?php echo $settings['currency'] === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                        <option value="EUR" <?php echo $settings['currency'] === 'EUR' ? 'selected' : ''; ?>>EUR (€)</option>
                                        <option value="GBP" <?php echo $settings['currency'] === 'GBP' ? 'selected' : ''; ?>>GBP (£)</option>
                                        <option value="INR" <?php echo $settings['currency'] === 'INR' ? 'selected' : ''; ?>>INR (₹)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="site_address" class="form-label">Site Address</label>
                                <textarea class="form-control" id="site_address" name="site_address" rows="3" required><?php 
                                    echo htmlspecialchars($settings['site_address']); 
                                ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                       value="<?php echo htmlspecialchars($settings['tax_rate']); ?>" 
                                       min="0" max="100" step="0.01" required>
                            </div>

                            <button type="submit" name="update_settings" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
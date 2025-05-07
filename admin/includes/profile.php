<!-- <?php
// Admin Profile Section
?>
<div class="admin-profile-card mb-4">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            <div class="profile-image">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
        <div class="col-md-8">
            <h3><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?></h3>
            <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'Not set'); ?></p>
            <p class="mb-1"><i class="fas fa-user-shield"></i> Administrator</p>
            <p class="mb-0"><i class="fas fa-calendar-alt"></i> Last Login: <?php echo date('F j, Y, g:i a', strtotime($_SESSION['last_login'] ?? 'now')); ?></p>
        </div>
        <div class="col-md-2 text-end">
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<style>
.admin-profile-card {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.profile-image i {
    font-size: 2.5rem;
    color: white;
}
</style>  -->
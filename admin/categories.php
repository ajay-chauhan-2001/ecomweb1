<?php
session_start();
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $where = "WHERE name LIKE '%$search%'";
}

// Get total number of categories
$total_sql = "SELECT COUNT(*) as total FROM categories $where";
$total_result = $conn->query($total_sql);
$total_categories = $total_result->fetch_assoc()['total'];

// Get categories for current page
$sql = "SELECT * FROM categories $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$page_title = "Manage Categories";
require_once 'includes/header.php';
?>

<!-- Sidebar -->
<?php require_once 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="col-md-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Categories Management</h2>
        <a href="add-category.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <form class="mb-3" method="GET" action="">
        <div class="input-group">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search category...">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="categories-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = ($page - 1) * $limit + 1;
                        foreach ($categories as $category):
                        ?>
                            <tr>
                                <td><?php echo $serial++; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td>
                                    <img src="../assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                         width="50">  
                                </td>
                                <td>
                                    <a href="edit-category.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteCategory(<?php echo $category['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Showing entries -->
            <div class="mt-3">
                <?php
                $start = ($page - 1) * $limit + 1;
                $end = $start + count($categories) - 1;
                if ($total_categories > 0):
                ?>
                    <p class="text-muted">
                        Showing <?php echo $start; ?> to <?php echo $end; ?> of <?php echo $total_categories; ?> entries
                    </p>
                <?php else: ?>
                    <p class="text-muted">No categories found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination links -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    $total_pages = ceil($total_categories / $limit);
                    for ($i = 1; $i <= $total_pages; $i++):
                    ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- SweetAlert Delete Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteCategory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This category will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete-category.php?id=' + id;
        }
    })
}
</script>

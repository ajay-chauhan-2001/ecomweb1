<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------
// Product Functions
// -------------------------------

// Get all products
function getAllProducts() {
    global $conn;
    $sql = "
        SELECT p.*, c.name as category_name,  p.image
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
    ";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get single product by ID
function getProductById($productId) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, name, price,description, sale_price, stock, image FROM products WHERE id = ?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $productId);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();

    return $product ?: false;
}

// -------------------------------
// Cart Functions
// -------------------------------

// Add to cart
function addToCart($product_id, $quantity = 1) {
    global $conn;

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;

    // Fix: Use custom guest ID
    if (!$userId) {
        if (!isset($_SESSION['guest_id'])) {
            $randomNumber = random_int(10000, 99999);
            $datePart = date('Ymd');
            $_SESSION['guest_id'] = 'guest_' . $datePart . '_' . $randomNumber;
        }
        $sessionId = $_SESSION['guest_id'];
    } else {
        $sessionId = null;
    }

    // Validate product
    $product = getProductById($product_id);
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }

    // Check if item already exists in cart
    if ($userId) {
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $userId, $product_id);
    } else {
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param('si', $sessionId, $product_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();
    $stmt->close();

    if ($existing) {
        // Already in cart — update quantity
        $newQuantity = $existing['quantity'] + $quantity;

        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param('ii', $newQuantity, $existing['id']);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new cart row
        if ($userId) {
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $userId, $product_id, $quantity);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param('sii', $sessionId, $product_id, $quantity);
        }
        $stmt->execute();
        $stmt->close();
    }

    return ['success' => true, 'message' => 'Product added to cart successfully.', 'cart_count' => getCartCount()];
}



// Update cart item quantity
function updateCartItem($productId, $quantity) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $productId = (int)$productId;
    $quantity = (int)$quantity;

    if ($quantity < 1) {
        return ['success' => false, 'message' => 'Invalid quantity'];
    }

    $product = getProductById($productId);
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }

    if ($quantity > $product['stock']) {
        $quantity = $product['stock'];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'product_id' => $productId,
            'quantity' => $quantity
        ];
    }

    $price = ($product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
    $subtotal = $price * $quantity;

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $prod = getProductById($item['product_id']);
        if ($prod) {
            $prodPrice = ($prod['sale_price'] > 0) ? $prod['sale_price'] : $prod['price'];
            $total += $prodPrice * $item['quantity'];
        }
    }

    return [
        'success' => true,
        'subtotal' => number_format($subtotal, 2),
        'total' => number_format($total, 2),
        'corrected' => ($quantity != $product['stock']) ? false : true,
        'message' => ($quantity == $product['stock']) ? 'Quantity adjusted to available stock.' : '',
        'cart_count' => getCartCount()
    ];
}

// Remove item from cart
function removeFromCart($productId) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $productId = (int)$productId;
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        return ['success' => true];
    }

    return ['success' => false, 'message' => 'Item not found in cart'];
}

function getAllProductImages() {
    $conn = getDBConnection();
    $sql = "SELECT 
                p.id as product_id,
                p.name as product_name,
                p.price,
                CONCAT('assets/images/products/', p.image) AS image_path
            FROM products p
            WHERE p.image IS NOT NULL AND p.image != ''
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);

    $images = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
    }

    return $images;
}


function getSessionId() {
    if (!isset($_SESSION)) {
        session_start();
    }

    // If session has custom guest id, use it
    if (!empty($_SESSION['guest_id'])) {
        return $_SESSION['guest_id'];
    }

    // Otherwise use default PHP session id
    return session_id();
}

function getCartItems($session_id) {
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'] ?? 0;
    $items = [];

    if ($user_id > 0) {
        // Logged in user
        $stmt = $conn->prepare("
            SELECT c.id as cart_id, p.id, p.name, p.price,p.sale_price, p.image, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->bind_param('i', $user_id);
    } else {
        // Guest user
        $stmt = $conn->prepare("
            SELECT c.id as cart_id, p.id, p.name, p.price,p.sale_price, p.image, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ?
        ");
        $stmt->bind_param('s', $session_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    

    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $items[] = $row;
    }

    return ['items' => $items, 'total' => $total];
}

function calculateCartTotal($cartItems) {
    return $cartItems['total'];
}

function clearCart() {
    $conn = getDBConnection();

    if (isset($_SESSION['user_id'])) {
        // Logged in user
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
    } else {
        // Guest user
        $session_id = getSessionId();
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt->bind_param('s', $session_id);
    }

    $stmt->execute();
}

// Get cart item count
function getCartCount() {
    global $conn;

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    $sessionId = session_id();

    if ($userId) {
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
    } else {
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ?");
        $stmt->bind_param('s', $sessionId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return (int)($row['total'] ?? 0);
}


function cleanInvalidCartProducts() {
    $conn = getDBConnection();
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? 0;

    $stmt = $conn->prepare("
        DELETE c FROM cart c
        LEFT JOIN products p ON c.product_id = p.id
        WHERE p.id IS NULL AND (c.user_id = ? OR c.session_id = ?)
    ");
    $stmt->bind_param('is', $user_id, $session_id);
    $stmt->execute();
}


// Get all cart items with product info

// -------------------------------
// User Functions
// -------------------------------

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current logged in user
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'User'
        ];
    }
    return null;
}

// Redirect to URL
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// -------------------------------
// Category Functions
// -------------------------------

// Fetch all categories
function getAllCategories() {
    global $conn;
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch category image
function getCategoryImage($categoryId) {
    $imagePath = "category-$categoryId.jpg";
    if (file_exists(__DIR__ . "/../assets/images/categories/$imagePath")) {
        return $imagePath;
    }
    return "default-category.jpg";
}

// Get product count by category
function getProductCountByCategory($categoryId) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ? AND status = 'active'");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result ? $result['count'] : 0;
}

// -------------------------------
// Filtering & Search
// -------------------------------

// Fetch filtered products
function getFilteredProducts($categoryName = '', $search = '', $sort = 'newest', $page = 1, $perPage = 12) {
    global $conn;

    $params = [];
    $types = '';
    $where = "WHERE p.status = 'active'";

    if (!empty($categoryName)) {
        $where .= " AND c.name = ?";
        $params[] = $categoryName;
        $types .= 's';
    }

    if (!empty($search)) {
        $where .= " AND p.name LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= 's';
    }

    // Sorting
    switch ($sort) {
        case 'price-low':
            $orderBy = "ORDER BY COALESCE(p.sale_price, p.price) ASC";
            break;
        case 'price-high':
            $orderBy = "ORDER BY COALESCE(p.sale_price, p.price) DESC";
            break;
        default:
            $orderBy = "ORDER BY p.created_at DESC";
            break;
    }

    // Pagination
    $offset = ($page - 1) * $perPage;
    $limit = "LIMIT ? OFFSET ?";
    $params[] = $perPage;
    $params[] = $offset;
    $types .= 'ii';

    $sql = "
        SELECT p.*, c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        $where
        $orderBy
        $limit
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}


// Get total products for pagination
function getTotalProducts($categoryName = '', $search = '') {
    global $conn;

    $params = [];
    $types = '';
    $where = "WHERE p.status = 'active'";

    if (!empty($categoryName)) {
        $where .= " AND c.name = ?";
        $params[] = $categoryName;
        $types .= 's';
    }

    if (!empty($search)) {
        $where .= " AND p.name LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= 's';
    }

    $sql = "
        SELECT COUNT(*) as total
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        $where
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Only bind if there are parameters
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'] ?? 0;
}



function getAllCategoriesWithCount() {
    $conn = getDBConnection();
    $sql = "SELECT c.id, c.name, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

?>

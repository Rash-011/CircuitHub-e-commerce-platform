<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// 1. Database Connection Configuration (Mirrored to match your homepage)
$host    = '127.0.0.1';
$port    = '3307';
$db      = 'circuithub_db';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database failure: ' . $e->getMessage()]);
    exit;
}

// 2. Identify the active User (Fallback to 1 if not logged in)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// 3. Process the Incoming request data
// productId is now the real numeric products.id (sent from homePage.php)
$data = json_decode(file_get_contents('php://input'), true);
$product_identifier = isset($data['product']) ? trim((string) $data['product']) : '';

if (empty($product_identifier) || !ctype_digit($product_identifier)) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing product ID']);
    exit;
}

try {
    // Make sure the product actually exists before wishlisting it
    $product_check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE id = :product_id");
    $product_check->execute(['product_id' => $product_identifier]);
    if ($product_check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // Check if it already exists in their wishlist to avoid duplicates
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
    $check_stmt->execute(['user_id' => $user_id, 'product_id' => $product_identifier]);
    $exists = $check_stmt->fetchColumn();

    if ($exists == 0) {
        // Insert new record into your wishlist table
        $insert_stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)");
        $insert_stmt->execute(['user_id' => $user_id, 'product_id' => $product_identifier]);
    }

    // Grab the new total live count for this user to push straight back to the UI header counter
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id");
    $count_stmt->execute(['user_id' => $user_id]);
    $new_count = $count_stmt->fetchColumn();

    echo json_encode(['success' => true, 'new_count' => $new_count]);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

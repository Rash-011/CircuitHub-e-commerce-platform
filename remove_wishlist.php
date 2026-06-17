<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

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
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

$input = json_decode(file_get_contents('php://input'), true);
$wishlist_id = isset($input['wishlist_id']) ? (int) $input['wishlist_id'] : 0;

if ($wishlist_id > 0) {
    try {
        // 1. Delete the targeted item row safely
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $wishlist_id, 'user_id' => $user_id]);

        // 2. Count remaining records directly from the database table to guarantee accuracy
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = :user_id");
        $count_stmt->execute(['user_id' => $user_id]);
        $new_count = (int) $count_stmt->fetchColumn();

        echo json_encode(['success' => true, 'new_count' => $new_count]);
        exit;
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid Wishlist Item ID']);
exit;

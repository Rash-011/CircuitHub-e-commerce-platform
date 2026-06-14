<?php
// api.php
header('Content-Type: application/json'); // Tell the browser we are sending JSON
require_once 'db.php';

try {
    // Fetch all products from the database
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Output the data as a JSON array
    echo json_encode($products);
} catch (Exception $e) {
    // If something goes wrong, send an error in JSON format
    echo json_encode(['error' => $e->getMessage()]);
}
?>
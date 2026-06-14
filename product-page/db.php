<?php
// db.php
$host = 'localhost';
$dbname = 'circuithub_db';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ensure we fetch data as associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // ---> NEW ERROR HANDLING <---
    
    // 1. Tell the browser/JS that we are sending JSON data
    header('Content-Type: application/json');
    
    // 2. Set an HTTP 500 Internal Server Error status code
    http_response_code(500);
    
    // 3. Output the error as a properly formatted JSON object
    echo json_encode([
        "error" => "Database Connection Failed",
        "message" => $e->getMessage()
    ]);
    
    // 4. Stop the script immediately so it doesn't try to load api.php
    exit();
}
?>
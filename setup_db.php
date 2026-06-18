<?php
// Connect to MySQL server
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database for CircuitHub
mysqli_query($con, "DROP DATABASE IF EXISTS circuithub_db");
$sql_db = "CREATE DATABASE IF NOT EXISTS circuithub_db";
if (mysqli_query($con, $sql_db)) {
    mysqli_select_db($con, "circuithub_db");

    // Create users table with shipping address
    $sql_table = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(15) NOT NULL,
        shipping_address TEXT NOT NULL,
        password VARCHAR(255) NOT NULL
    )";

    if (mysqli_query($con, $sql_table)) {
        echo "CircuitHub Database & Users table created successfully!";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }
} else {
    echo "Error creating database: " . mysqli_error($con);
}

mysqli_close($con);
?>
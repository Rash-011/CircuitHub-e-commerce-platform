<?php
include("connection.php");

$name = trim($_POST['uName']);
$email = trim($_POST['uEmail']);
$phone = trim($_POST['uPhone']);
$address = trim($_POST['uAddress']);
$pwd = $_POST['uPwd'];

// Server-side check
if(empty($name) || empty($email) || empty($phone) || empty($address) || empty($pwd)){
    die("Error: Please fill all fields.");
}

// Security hash
$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, phone, shipping_address, password) 
        VALUES ('$name', '$email', '$phone', '$address', '$hashed_pwd')";

if (mysqli_query($con, $sql)) {
    echo "<script>alert('Registration Successful to CircuitHub!'); window.location.href='login.php';</script>";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>
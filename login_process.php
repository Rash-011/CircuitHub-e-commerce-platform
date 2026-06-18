<?php
// Start active session tracker
session_start();
include("connection.php");

// Turn on error reporting to see problems if any
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get POST data safely from form
$email = isset($_POST['logEmail']) ? trim($_POST['logEmail']) : '';
$pwd = isset($_POST['logPwd']) ? $_POST['logPwd'] : '';

// Check if inputs are empty
if(empty($email) || empty($pwd)){
    echo "<script>alert('Please fill all fields.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch user from database using email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($con, $sql);

// Check if user exists
if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    
    // Verify password with hashed password in database
    if (password_verify($pwd, $row['password'])) {
        
        // Save user data inside sessions
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        
        // Handle "Remember Me" Checkbox
        if (isset($_POST['rememberMe'])) {
            // Save inside browser cookies for 7 days
            setcookie("user_email", $email, time() + (86400 * 7));
            setcookie("user_password", $pwd, time() + (86400 * 7)); 
        } else {
            // Clear old cookies if unticked
            setcookie("user_email", "", time() - 3600);
            setcookie("user_password", "", time() - 3600);
        }
        
        // Login success: Go to home dashboard
        echo "<script>alert('Welcome back to CircuitHub!'); window.location.href='home.php';</script>";
        exit();
        
    } else {
        // Handle wrong password error
        echo "<script>alert('Invalid password! Please try again.'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    // Handle wrong email error
    echo "<script>alert('No account found with that email.'); window.location.href='login.php';</script>";
    exit();
}

// Close database connection
mysqli_close($con);
?>
<?php
// Start session to access logged-in user data
session_start();

// Security check: If user is not logged in, kick them back to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CircuitHub - Dashboard</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            padding: 40px; 
            background: #f4f6f9; 
            text-align: center;
        }
        .dashboard { 
            background: white; 
            padding: 40px; 
            display: inline-block; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
            max-width: 500px;
            width: 100%;
        }
        h1 { color: #333; }
        p { color: #666; font-size: 16px; }
        
        /* Beautiful red logout button style */
        .logout-btn { 
            background: #dc3545; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: bold; 
            display: inline-block; 
            margin-top: 25px;
            transition: background 0.3s;
        }
        .logout-btn:hover { 
            background: #c82333; 
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Welcome to CircuitHub, <?php echo $_SESSION['user_name']; ?>!</h1>
    <p>Success! You are safely logged in. Now you can buy your favorite Sensors, Arduino modules, and electronic components.</p>
    
    <a href="logout.php" class="logout-btn">Log Out</a>
</div>

</body>
</html>
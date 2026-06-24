<?php
// Check if cookies exist in browser storage
$cookie_email = isset($_COOKIE["user_email"]) ? $_COOKIE["user_email"] : "";
$cookie_pwd = isset($_COOKIE["user_password"]) ? $_COOKIE["user_password"] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CircuitHub - Login</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #f4f6f9; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-box { 
            background: #fff; 
            width: 100%; 
            max-width: 360px; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: #555; font-size: 14px; }
        input[type="text"], input[type="password"] { 
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; 
        }
        input:focus { border-color: #8a63d2; outline: none; }
        .remember-group { display: flex; align-items: center; margin: 15px 0; font-size: 14px; color: #555;}
        .remember-group input { margin-right: 8px; cursor: pointer; }
        .btn { 
            width: 100%; background: linear-gradient(to right, #4f6be3, #8258dc); color: #fff; 
            padding: 12px; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; 
        }
        .btn:hover { opacity: 0.9; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .link a { color: #4f6be3; text-decoration: none; font-weight: bold; }
        .error-text { color: #dc3545; font-size: 12px; margin-top: 4px; display: block; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>CircuitHub Login</h2>
    <form name="loginForm" action="login_process.php" method="POST" onsubmit="return validateLogin()">
        
        <div class="input-group">
            <label>Email</label>
            <input type="text" name="logEmail" value="<?php echo htmlspecialchars($cookie_email); ?>">
            <span id="logEmailErr" class="error-text"></span>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="logPwd" value="<?php echo htmlspecialchars($cookie_pwd); ?>">
            <span id="logPwdErr" class="error-text"></span>
        </div>

        <div class="remember-group">
            <input type="checkbox" name="rememberMe" <?php if($cookie_email != "") { echo "checked"; } ?>>
            <label style="margin:0; font-weight:500;">Remember Me</label>
        </div>

        <button type="submit" class="btn">Sign In</button>
        <div class="link">New to CircuitHub? <a href="registration.php">Register</a></div>
    </form>
</div>

<script>
function validateLogin() {
    document.getElementById("logEmailErr").innerHTML = "";
    document.getElementById("logPwdErr").innerHTML = "";

    var email = document.forms["loginForm"]["logEmail"].value.trim();
    var pwd = document.forms["loginForm"]["logPwd"].value;
    var status = true;

    if (email == "") { document.getElementById("logEmailErr").innerHTML = "Email is required."; status = false; }
    if (pwd == "") { document.getElementById("logPwdErr").innerHTML = "Password is required."; status = false; }

    return status;
}
</script>
</body>
</html>
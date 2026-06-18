<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CircuitHub - Sign Up</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-box { background: #fff; width: 100%; max-width: 420px; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        h2 { text-align: center; color: #333; margin-bottom: 5px; }
        p { text-align: center; color: #666; font-size: 13px; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: #555; font-size: 14px; }
        input[type="text"], input[type="password"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input:focus, textarea:focus { border-color: #8a63d2; outline: none; box-shadow: 0 0 0 3px rgba(138, 99, 210, 0.15); }
        textarea { height: 60px; resize: none; }
        .error-text { color: #dc3545; font-size: 12px; margin-top: 4px; display: block; }
        .btn { width: 100%; background: linear-gradient(to right, #4f6be3, #8258dc); color: #fff; padding: 12px; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn:hover { opacity: 0.9; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .link a { color: #4f6be3; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>CircuitHub</h2>
    <p>Create an account to buy Sensors & Arduino modules</p>
    
    <form name="regForm" action="register_process.php" method="POST" onsubmit="return validateForm()">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="uName">
            <span id="nameErr" class="error-text"></span>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="text" name="uEmail">
            <span id="emailErr" class="error-text"></span>
        </div>
        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" name="uPhone" placeholder="07XXXXXXXX">
            <span id="phoneErr" class="error-text"></span>
        </div>
        <div class="input-group">
            <label>Shipping Address</label>
            <textarea name="uAddress" placeholder="Enter delivery address"></textarea>
            <span id="addressErr" class="error-text"></span>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="uPwd">
            <span id="pwdErr" class="error-text"></span>
        </div>
        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="uCpwd">
            <span id="cpwdErr" class="error-text"></span>
        </div>
        <button type="submit" class="btn">Register</button>
        <div class="link">Already have an account? <a href="login.php">Login</a></div>
    </form>
</div>

<script>
function validateForm() {
    // Clear errors
    document.getElementById("nameErr").innerHTML = "";
    document.getElementById("emailErr").innerHTML = "";
    document.getElementById("phoneErr").innerHTML = "";
    document.getElementById("addressErr").innerHTML = "";
    document.getElementById("pwdErr").innerHTML = "";
    document.getElementById("cpwdErr").innerHTML = "";

    var name = document.forms["regForm"]["uName"].value.trim();
    var email = document.forms["regForm"]["uEmail"].value.trim();
    var phone = document.forms["regForm"]["uPhone"].value.trim();
    var address = document.forms["regForm"]["uAddress"].value.trim();
    var pwd = document.forms["regForm"]["uPwd"].value;
    var cpwd = document.forms["regForm"]["uCpwd"].value;

    var status = true;

    if (name == "") { document.getElementById("nameErr").innerHTML = "Name is required."; status = false; }

    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (email == "") { document.getElementById("emailErr").innerHTML = "Email is required."; status = false; }
    else if (!email.match(emailPattern)) { document.getElementById("emailErr").innerHTML = "Invalid email format."; status = false; }

    var phonePattern = /^07[0-9]{8}$/;
    if (phone == "") { document.getElementById("phoneErr").innerHTML = "Phone number is required."; status = false; }
    else if (isNaN(phone)) { document.getElementById("phoneErr").innerHTML = "Numbers only."; status = false; }
    else if (!phone.match(phonePattern)) { document.getElementById("phoneErr").innerHTML = "Use 07XXXXXXXX format."; status = false; }

    if (address == "") { document.getElementById("addressErr").innerHTML = "Shipping address is required."; status = false; }
    if (pwd == "") { document.getElementById("pwdErr").innerHTML = "Password is required."; status = false; }
    else if (pwd.length < 6) { document.getElementById("pwdErr").innerHTML = "Must be at least 6 characters."; status = false; }

    if (cpwd == "") { document.getElementById("cpwdErr").innerHTML = "Please re-type password."; status = false; }
    else if (pwd !== cpwd) { document.getElementById("cpwdErr").innerHTML = "Passwords do not match."; status = false; }

    return status; 
}
</script>
</body>
</html>
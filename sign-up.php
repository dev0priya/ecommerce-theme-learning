<?php
require 'include/load.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender   = $_POST['gender'] ?? null;

    // ===== Validation =====
    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } 
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } 
    else {

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $error = "Email already registered.";
        } else {

            // Secure password hash
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, gender, role)
                VALUES (?, ?, ?, ?, 'user')
            ");

            $stmt->execute([
                $name,
                $email,
                $hashedPassword,
                $gender
            ]);

            $success = "Account created successfully! You can now login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<style>
body{
    background:#0f172a;
    font-family:Arial;
    color:white;
}
.container{
    width:400px;
    margin:100px auto;
    background:#1e293b;
    padding:30px;
    border-radius:10px;
}
input, select{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:none;
}
button{
    width:100%;
    padding:10px;
    background:#3b82f6;
    border:none;
    color:white;
    border-radius:6px;
}
.error{color:#ef4444;}
.success{color:#10b981;}
a{color:#3b82f6;}
</style>
</head>

<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <select name="gender">
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
        </select>

        <button type="submit">Sign Up</button>
    </form>

    <p style="margin-top:15px;">
        Already have account? <a href="sign-in.php">Login</a>
    </p>
</div>

</body>
</html>
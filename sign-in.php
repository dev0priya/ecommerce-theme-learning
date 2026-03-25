<?php
require 'include/load.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "All fields are required.";
    } else {

        // Fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {

            // Store session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                redirect('dashboard.php');          // Admin dashboard
            } else {
                redirect('modules/user/dashboard.php');     // Normal user dashboard
            }

        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <style>
        body{
            background:#0f172a;
            font-family:Arial;
            color:white;
        }
        .container{
            width:400px;
            margin:120px auto;
            background:#1e293b;
            padding:30px;
            border-radius:10px;
        }
        input{
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
            cursor:pointer;
        }
        .error{
            color:#ef4444;
        }
        a{
            color:#3b82f6;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Sign In</h2>

    <?php if ($error): ?>
        <p class="error"><?= e($error); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

    <p style="margin-top:15px;">
        Don’t have account? <a href="sign-up.php">Sign Up</a>
    </p>
</div>

</body>
</html>
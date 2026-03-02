<?php
require '../include/load.php';
checkLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];
    $pass  = $_POST['password'];

    // 1. Basic Validation
    if (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // 2. Hash Password (CRITICAL)
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        try {
            // 3. Insert into Database
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$name, $email, $hashed_pass, $role]);

            // 4. Redirect to user list
            redirect('index.php');

        } catch (PDOException $e) {
            $error = "Error: Email might already exist.";
        }
    }
}

include '../partials/head.php';
?>

<body>
    <?php include '../partials/sidebar.php'; ?>

    <div class="content">
        <h2>Add New User</h2>

        <?php if ($error): ?>
            <p style="color:red"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Role:</label><br>
            <select name="role">
                <option value="user">Customer</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit">Save User</button>
        </form>
    </div>
</body>
</html>

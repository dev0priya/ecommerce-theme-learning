<?php
require '../include/load.php';
checkLogin();

// 1. Get user ID from URL (edit.php?id=5)
$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// 2. Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

// 3. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    // Update password ONLY if new one is entered
    if (!empty($_POST['password'])) {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?";
        $params = [$name, $email, $role, $hashed_pass, $id];
    } else {
        // Keep old password
        $sql = "UPDATE users SET name=?, email=?, role=? WHERE id=?";
        $params = [$name, $email, $role, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    redirect('index.php');
}

include '../partials/head.php';
?>

<body>
    <?php include '../partials/sidebar.php'; ?>

    <div class="content">
        <h2>Edit User</h2>

        <form method="POST">
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required><br><br>

            <label>New Password (leave blank to keep current):</label><br>
            <input type="password" name="password"><br><br>

            <label>Role:</label><br>
            <select name="role">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Customer</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select><br><br>

            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>

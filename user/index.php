<?php
require '../include/load.php';
checkLogin();

// 1. Fetch all users from Database
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include '../partials/head.php';
?>

<body>
    <?php include '../partials/sidebar.php'; ?>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Manage Users</h1>
            <a href="add.php" style="background: green; color: white; padding: 10px; text-decoration: none;">
                + Add New User
            </a>
        </div>

        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr style="background: #eee;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= e($u['id']) ?></td>
                    <td><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['role']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $u['id'] ?>">Edit</a>
                        |
                        <a href="#" onclick="deleteItem(<?= $u['id'] ?>, 'users')" style="color: red;">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/app.js"></script>
</body>
</html>

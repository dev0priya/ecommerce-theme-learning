<?php
require 'include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];

$error = '';
$success = '';

// Get current data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $gender = $_POST['gender'] ?? null;

    $avatarName = $user['avatar'];

    // If new image uploaded
    if (!empty($_FILES['avatar']['name'])) {
        $upload = uploadImage($_FILES['avatar'], 'avatars');
        if ($upload) {
            $avatarName = $upload;
        }
    }

    $stmt = $pdo->prepare("
        UPDATE users 
        SET name = ?, gender = ?, avatar = ?
        WHERE id = ?
    ");

    $stmt->execute([$name, $gender, $avatarName, $userId]);

    $success = "Profile updated successfully!";
}

include 'partials/head.php';
?>

<h2>Edit Profile</h2>

<?php if($success): ?>
<p style="color:green;"><?= $success ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Name</label><br>
<input type="text" name="name" value="<?= e($user['name']) ?>"><br><br>

<label>Gender</label><br>
<select name="gender">
    <option <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
    <option <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
</select><br><br>

<label>Profile Picture</label><br>
<input type="file" name="avatar"><br><br>

<?php if ($user['avatar']): ?>
<img src="assets/avatars/<?= e($user['avatar']) ?>" width="100">
<?php endif; ?>

<button type="submit">Update</button>

</form>
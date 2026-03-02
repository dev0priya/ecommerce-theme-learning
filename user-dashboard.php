<?php
require 'include/load.php';
checkLogin();

if ($_SESSION['user_role'] !== 'user') {
    redirect('dashboard.php');
}

$userId = $_SESSION['user_id'];

/* ---------------- USER DATA ---------------- */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

/* ---------------- STATS ---------------- */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$userId]);
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(order_items.price * order_items.quantity),0)
    FROM orders
    JOIN order_items ON order_items.order_id = orders.id
    WHERE orders.user_id = ?
");
$stmt->execute([$userId]);
$totalSpent = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM orders 
    WHERE user_id = ? AND status='Pending'
");
$stmt->execute([$userId]);
$pendingOrders = $stmt->fetchColumn();

/* ---------------- ADDRESS LOGIC ---------------- */

// Add Address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {

    $address = $_POST['address'];
    $city    = $_POST['city'];
    $state   = $_POST['state'];
    $zip     = $_POST['zip'];

    $stmt = $pdo->prepare("
        INSERT INTO user_addresses (user_id, address, city, state, zip)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $address, $city, $state, $zip]);
}

// Delete Address
if (isset($_GET['delete_address'])) {
    $id = $_GET['delete_address'];

    $stmt = $pdo->prepare("
        DELETE FROM user_addresses 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$id, $userId]);
}

// Set Default
if (isset($_GET['set_default'])) {
    $id = $_GET['set_default'];

    $pdo->prepare("
        UPDATE user_addresses SET is_default = 0 
        WHERE user_id = ?
    ")->execute([$userId]);

    $pdo->prepare("
        UPDATE user_addresses SET is_default = 1 
        WHERE id = ? AND user_id = ?
    ")->execute([$id, $userId]);
}

// Fetch Addresses
$stmt = $pdo->prepare("
    SELECT * FROM user_addresses 
    WHERE user_id = ?
    ORDER BY is_default DESC
");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll();

/* ---------------- CHANGE PASSWORD ---------------- */

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
    $stmt->execute([$userId]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($current,$hash)) {
        $error = "Current password incorrect.";
    }
    elseif ($new !== $confirm) {
        $error = "Passwords do not match.";
    }
    else {
        $newHash = password_hash($new,PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password=? WHERE id=?")
            ->execute([$newHash,$userId]);
        $success="Password updated successfully.";
    }
}

include 'partials/head.php';
?>

<style>
.admin-layout{display:flex;min-height:100vh;}
.content{flex:1;padding:30px;background:#f4f6f9;}
.profile-card{background:#fff;border-radius:12px;padding:30px;display:flex;align-items:center;gap:30px;box-shadow:0 4px 10px rgba(0,0,0,0.05);}
.profile-avatar{width:120px;height:120px;border-radius:50%;object-fit:cover;}
.stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:30px;}
.stat-box{background:#fff;padding:20px;border-radius:10px;box-shadow:0 3px 8px rgba(0,0,0,0.05);}
.stat-number{font-size:22px;font-weight:bold;margin-top:5px;}
.tabs button{padding:10px 20px;margin-right:10px;border:none;background:#ddd;border-radius:6px;cursor:pointer;}
.tab-content{margin-top:20px;background:#fff;padding:20px;border-radius:10px;}
</style>

<body>

<?php include 'partials/header.php'; ?>

<div class="admin-layout">

<?php include 'partials/sidebar-user.php'; ?>

<div class="content">

<h2>My Account</h2>

<!-- PROFILE CARD -->
<div class="profile-card">
    <img class="profile-avatar"
         src="assets/avatars/<?= $user['avatar'] ?? 'default.png' ?>">
    <div>
        <h3><?= e($user['name']) ?></h3>
        <p><?= e($user['email']) ?></p>
        <p>Member since <?= date('d M Y', strtotime($user['created_at'])) ?></p>
    </div>
</div>

<!-- STATS -->
<div class="stat-grid">
    <div class="stat-box">
        <h4>Total Orders</h4>
        <div class="stat-number"><?= $totalOrders ?></div>
    </div>
    <div class="stat-box">
        <h4>Pending Orders</h4>
        <div class="stat-number"><?= $pendingOrders ?></div>
    </div>
    <div class="stat-box">
        <h4>Total Spent</h4>
        <div class="stat-number">$<?= number_format($totalSpent,2) ?></div>
    </div>
</div>

<!-- TABS -->
<div style="margin-top:40px;" class="tabs">
    <button onclick="showTab('orders')">My Orders</button>
    <button onclick="showTab('address')">Addresses</button>
    <button onclick="showTab('security')">Security</button>
</div>

<!-- ORDERS -->
<div id="orders" class="tab-content">
    <p>Order system coming next step (timeline + tracking).</p>
</div>

<!-- ADDRESS -->
<div id="address" class="tab-content" style="display:none;">

<h3>Add New Address</h3>
<form method="POST">
    <input type="hidden" name="add_address" value="1">
    <textarea name="address" placeholder="Full Address" required></textarea><br><br>
    <input type="text" name="city" placeholder="City" required><br><br>
    <input type="text" name="state" placeholder="State" required><br><br>
    <input type="text" name="zip" placeholder="ZIP Code" required><br><br>
    <button type="submit">Save Address</button>
</form>

<hr><br>

<h3>Saved Addresses</h3>

<?php if (empty($addresses)): ?>
    <p>No addresses saved yet.</p>
<?php endif; ?>

<?php foreach ($addresses as $addr): ?>
<div style="border:1px solid #ddd;padding:15px;margin-bottom:10px;border-radius:6px;">
    <?php if($addr['is_default']): ?>
        <strong style="color:green;">Default Address</strong><br>
    <?php endif; ?>
    <?= e($addr['address']) ?><br>
    <?= e($addr['city']) ?>,
    <?= e($addr['state']) ?> -
    <?= e($addr['zip']) ?><br><br>

    <?php if(!$addr['is_default']): ?>
        <a href="?set_default=<?= $addr['id'] ?>">Set as Default</a> |
    <?php endif; ?>

    <a href="?delete_address=<?= $addr['id'] ?>"
       onclick="return confirm('Delete this address?')"
       style="color:red;">Delete</a>
</div>
<?php endforeach; ?>

</div>

<!-- SECURITY -->
<div id="security" class="tab-content" style="display:none;">

<h3>Change Password</h3>

<?php if($error): ?>
<p style="color:red;"><?= e($error) ?></p>
<?php endif; ?>

<?php if($success): ?>
<p style="color:green;"><?= e($success) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="change_password" value="1">
    <label>Current Password</label><br>
    <input type="password" name="current_password" required><br><br>

    <label>New Password</label><br>
    <input type="password" name="new_password" required><br><br>

    <label>Confirm Password</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Update Password</button>
</form>

</div>

</div>
</div>

<script>
function showTab(id){
    document.querySelectorAll('.tab-content')
        .forEach(el=>el.style.display='none');
    document.getElementById(id).style.display='block';
}
</script>

</body>
</html>
<?php
require '../include/load.php';
checkLogin();

$title  = getSetting('site_title', $pdo);
$email  = getSetting('contact_email', $pdo);
$footer = getSetting('footer_text', $pdo);

include '../partials/head.php';
?>

<body>
<?php include '../partials/sidebar.php'; ?>

<div class="content">
    <h1>General Settings</h1>

    <form action="../api/settings/update.php" method="POST" enctype="multipart/form-data">

        <label>Website Title:</label><br>
        <input type="text" name="site_title" value="<?= e($title) ?>"><br><br>

        <label>Contact Email:</label><br>
        <input type="email" name="contact_email" value="<?= e($email) ?>"><br><br>

        <label>Footer Text:</label><br>
        <input type="text" name="footer_text" value="<?= e($footer) ?>"><br><br>

        <label>Current Logo:</label><br>
        <img src="../assets/uploads/<?= getSetting('site_logo', $pdo) ?>" width="100"><br><br>

        <label>Change Logo:</label><br>
        <input type="file" name="site_logo"><br><br>

        <button type="submit">Save Settings</button>
    </form>
</div>
</body>

<header class="site-header">
    <nav style="background:#333; color:#fff; padding:10px; display:flex; justify-content:space-between;">
    
    <div style="display:flex; align-items:center; gap:10px;">
        <img 
            src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" 
            height="40"
        >

        <a href="<?= BASE_URL ?>/index.php"
           style="color:white; text-decoration:none; font-weight:bold;">
            <?= e(getSetting('site_title', $pdo)); ?>
        </a>
    </div>

    <div>
        <a href="<?= BASE_URL ?>/cart/index.php" style="color:white;">
            Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>/dashboard.php" style="color:yellow; margin-left:10px;">
                Dashboard
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/sign-in.php" style="color:white; margin-left:10px;">
                Login
            </a>
        <?php endif; ?>
    </div>
</nav>

</header>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= e(getSetting('site_title', $pdo)); ?> - <?= $page_title ?? 'Home'; ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_favicon', $pdo); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
</head>
<body>

<header class="site-header">
    <div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
        <div class="flex items-center justify-between">
            
            <div class="col-auto">
                <div class="flex flex-wrap items-center gap-[16px]">
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <img 
                            src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" 
                            alt="Logo" 
                            class="w-10 h-10 object-cover"
                        >
                        <a href="<?= BASE_URL ?>/index.php" class="text-lg text-neutral-900 font-semibold mb-0 dark:text-white decoration-none">
                            <?= e(getSetting('site_title', $pdo)); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <div class="flex flex-wrap items-center gap-3">
                    
                    <button type="button" id="theme-toggle" class="w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center">
                        <iconify-icon icon="ri:sun-line" id="theme-toggle-dark-icon" class="hidden"></iconify-icon>
                        <iconify-icon icon="ri:moon-line" id="theme-toggle-light-icon" class="hidden"></iconify-icon>
                    </button>

                    <a href="<?= BASE_URL ?>/cart/index.php" class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center relative">
                        <iconify-icon icon="mage:email" class="text-neutral-900 dark:text-white text-xl"></iconify-icon>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary-600 text-white text-[10px] font-bold flex justify-center items-center rounded-full">
                            <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                        </span>
                    </a>

                    <div class="flex items-center gap-3">
                        <?php if (isset($_SESSION['user_id'])): ?>

    <?php
        $dashboardLink = ($_SESSION['user_role'] === 'admin')
            ? BASE_URL . '/dashboard.php'
            : BASE_URL . '/modules/user/dashboard.php';
    ?>

    <a href="<?= $dashboardLink ?>" 
       class="flex items-center gap-2 px-3 py-2 bg-primary-50 dark:bg-primary-600/25 rounded-lg text-primary-600 decoration-none">

        <iconify-icon icon="solar:user-linear" class="text-xl"></iconify-icon>
        <span class="text-sm font-semibold">Dashboard</span>

    </a>

<?php else: ?>
                            <a href="<?= BASE_URL ?>/sign-in.php" class="flex items-center gap-2 px-4 py-2 border border-neutral-200 dark:border-neutral-600 rounded-lg text-neutral-900 dark:text-white hover:text-primary-600 decoration-none">
                                <iconify-icon icon="lucide:power" class="text-xl"></iconify-icon>
                                <span class="text-sm font-medium">Login</span>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</header>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= e(getSetting('site_title', $pdo)); ?> - <?= $page_title ?? 'Home'; ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_favicon', $pdo); ?>">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">

    <!-- ICONIFY (REQUIRED) -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- 🔥 PREMIUM HEADER STYLE -->
    <style>
        .site-header {
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.8);
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-header {
            padding: 12px 20px;
        }

        /* LOGO */
        .site-header img {
            border-radius: 12px;
        }

        /* ICON BUTTONS */
        .icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            position: relative;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99,102,241,0.2);
        }

        .icon-btn iconify-icon {
            font-size: 20px;
            color: #4f46e5;
        }

        /* BADGE */
        .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f43f5e;
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* LOGIN BUTTON */
        .login-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white !important;
            border-radius: 10px;
            padding: 8px 14px;
            transition: 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99,102,241,0.3);
        }

        /* DASHBOARD */
        .dashboard-btn {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            color: white !important;
            border-radius: 10px;
            padding: 8px 14px;
        }
    </style>
</head>

<body>

<header class="site-header">
    <div class="navbar-header">
        <div class="flex items-center justify-between">
            
            <!-- LEFT -->
            <div class="flex items-center gap-4">

                <button type="button" class="sidebar-toggle icon-btn">
                    <iconify-icon icon="heroicons:bars-3-solid"></iconify-icon>
                </button>
                
                <div class="flex items-center gap-3">
                    <img 
                        src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" 
                        class="w-10 h-10 object-cover"
                    >
                    <a href="<?= BASE_URL ?>/index.php" class="text-lg font-semibold text-neutral-900">
                        <?= e(getSetting('site_title', $pdo)); ?>
                    </a>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-3">

                <!-- THEME -->
                <button type="button" id="theme-toggle" class="icon-btn">
                    <iconify-icon icon="ri:moon-line"></iconify-icon>
                </button>

                <!-- ❤️ WISHLIST -->
                <a href="<?= BASE_URL ?>/wishlist.php" class="icon-btn">
                    <iconify-icon icon="solar:heart-linear"></iconify-icon>
                </a>

                <!-- 🛒 CART -->
                <a href="<?= BASE_URL ?>/cart/index.php" class="icon-btn">
                    <iconify-icon icon="solar:cart-large-minimalistic-bold"></iconify-icon>
                    <span class="badge-count">
                        <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                    </span>
                </a>

                <!-- USER -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                        $dashboardLink = ($_SESSION['user_role'] === 'admin')
                            ? BASE_URL . '/dashboard.php'
                            : BASE_URL . '/modules/user/dashboard.php';
                    ?>
                    <a href="<?= $dashboardLink ?>" class="dashboard-btn flex items-center gap-2">
                        <iconify-icon icon="solar:user-bold"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/sign-in.php" class="login-btn flex items-center gap-2">
                        <iconify-icon icon="lucide:log-in"></iconify-icon>
                        <span>Login</span>
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
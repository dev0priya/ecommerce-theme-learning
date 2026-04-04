<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= e(getSetting('site_title', $pdo)); ?> - <?= $page_title ?? 'Home'; ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_favicon', $pdo); ?>">

    <!-- 🔥 APPLY THEME BEFORE LOAD -->
    <script>
    (function () {
        const theme = localStorage.getItem("theme");
        if (theme === "dark") {
            document.documentElement.classList.add("dark");
            document.documentElement.classList.remove("light");
        } else {
            document.documentElement.classList.add("light");
        }
    })();
    </script>

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
    

    <!-- ICONIFY -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- 🎨 PREMIUM HEADER STYLE -->
    <style>
        .site-header {
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,0.75);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        .dark .site-header {
            background: rgba(15,23,42,0.85);
            border-color: rgba(255,255,255,0.08);
        }

        .navbar-header {
            padding: 14px 24px;
        }

        .site-header img {
            border-radius: 12px;
        }

        .site-header a {
            font-weight: 700;
            letter-spacing: -0.4px;
            transition: 0.3s;
        }

        .site-header a:hover {
            color: #6366f1;
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #eef2ff, #ffffff);
            border: 1px solid rgba(99,102,241,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            position: relative;
        }

        .icon-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(99,102,241,0.25);
        }

        .icon-btn iconify-icon {
            font-size: 20px;
            color: #4f46e5;
        }

        .dark .icon-btn {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
        }

        .dark .icon-btn iconify-icon {
            color: #fff;
        }

        .badge-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: linear-gradient(135deg, #f43f5e, #ec4899);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .login-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white !important;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
        }

        .dashboard-btn {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            color: white !important;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
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
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" class="w-10 h-10 object-cover">
                    <a href="<?= BASE_URL ?>/index.php" class="text-lg text-neutral-900">
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

                <!-- ❤️ -->
                <a href="pages/wishlist.php" class="icon-btn">
                    <iconify-icon icon="solar:heart-linear"></iconify-icon>
                </a>

                <!-- 🛒 -->
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

<!-- 🔥 DARK MODE TOGGLE -->
<script>
const toggleBtn = document.getElementById("theme-toggle");
const html = document.documentElement;
const icon = toggleBtn.querySelector("iconify-icon");

// set icon on load
if (html.classList.contains("dark")) {
    icon.setAttribute("icon", "ri:sun-line");
} else {
    icon.setAttribute("icon", "ri:moon-line");
}

toggleBtn.addEventListener("click", () => {
    if (html.classList.contains("dark")) {
        html.classList.remove("dark");
        html.classList.add("light");
        localStorage.setItem("theme", "light");
        icon.setAttribute("icon", "ri:moon-line");
    } else {
        html.classList.remove("light");
        html.classList.add("dark");
        localStorage.setItem("theme", "dark");
        icon.setAttribute("icon", "ri:sun-line");
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
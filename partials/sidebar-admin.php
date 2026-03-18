<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layouts/sidebar.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    
    <div class="sidebar-logo">
        <a href="<?= BASE_URL ?>/dashboard.php" class="flex items-center">
            <img src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" alt="site logo" class="light-logo w-10 h-10">
            <span class="text-xl font-bold text-neutral-900 dark:text-white ms-2">Admin Panel</span>
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            
            <li class="sidebar-menu-group-title">Main Menu</li>
            
            <li>
                <a href="<?= BASE_URL ?>/dashboard.php">
                    <iconify-icon icon="solar:pie-chart-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:package-box" class="menu-icon"></iconify-icon>
                    <span>Products</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= BASE_URL ?>/products/index.php"><i class="ri-circle-fill circle-icon text-primary-600"></i> All Products</a></li>
                    <li><a href="<?= BASE_URL ?>/products/add.php"><i class="ri-circle-fill circle-icon text-info-600"></i> Add Product</a></li>
                    <li><a href="<?= BASE_URL ?>/products/categories.php"><i class="ri-circle-fill circle-icon text-warning-600"></i> Categories</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Sales & Orders</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= BASE_URL ?>/orders/index.php"><i class="ri-circle-fill circle-icon text-primary-600"></i> Order List</a></li>
                    <li><a href="<?= BASE_URL ?>/invoices/list.php"><i class="ri-circle-fill circle-icon text-info-600"></i> Invoices</a></li>
                </ul>
            </li>

            <li class="sidebar-menu-group-title">Application</li>

            <li>
                <a href="<?= BASE_URL ?>/email.php">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Email</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/chat-message.php">
                    <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                    <span>Chat</span>
                </a>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:ai-brain-03" class="menu-icon"></iconify-icon>
                    <span>AI Content Tools</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= BASE_URL ?>/ai/text-generator.php"><i class="ri-circle-fill circle-icon text-primary-600"></i> Description Gen</a></li>
                    <li><a href="<?= BASE_URL ?>/ai/image-generator.php"><i class="ri-circle-fill circle-icon text-info-600"></i> Image AI</a></li>
                </ul>
            </li>

            <li class="sidebar-menu-group-title">Users & Auth</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                    <span>Authentication</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= BASE_URL ?>/sign-in.php"><i class="ri-circle-fill circle-icon text-primary-600"></i> Sign In</a></li>
                    <li><a href="<?= BASE_URL ?>/sign-up.php"><i class="ri-circle-fill circle-icon text-warning-600"></i> Sign Up</a></li>
                    <li><a href="<?= BASE_URL ?>/forgot-password.php"><i class="ri-circle-fill circle-icon text-info-600"></i> Forgot Password</a></li>
                </ul>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/user/index.php">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Customers List</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Settings & Support</li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                    <span>Site Settings</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="<?= BASE_URL ?>/settings/index.php"><i class="ri-circle-fill circle-icon text-primary-600"></i> General Settings</a></li>
                    <li><a href="<?= BASE_URL ?>/settings/payment-gateway.php"><i class="ri-circle-fill circle-icon text-success-600"></i> Payment Gateway</a></li>
                    <li><a href="<?= BASE_URL ?>/settings/currencies.php"><i class="ri-circle-fill circle-icon text-warning-600"></i> Currencies</a></li>
                </ul>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/faq.php">
                    <iconify-icon icon="mage:message-question-mark-round" class="menu-icon"></iconify-icon>
                    <span>FAQs & Support</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/error.php">
                    <iconify-icon icon="streamline:straight-face" class="menu-icon"></iconify-icon>
                    <span>404 Error Page</span>
                </a>
            </li>

            <li class="mt-4">
                <a href="<?= BASE_URL ?>/logout.php" class="text-danger-600">
                    <iconify-icon icon="lucide:power" class="menu-icon text-danger-600"></iconify-icon>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script src="<?= rtrim(BASE_URL, '/') ?>/assets/js/layouts/sidebar.js"></script>
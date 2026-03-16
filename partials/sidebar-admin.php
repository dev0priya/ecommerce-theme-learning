<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    
    <div>
        <a href="<?= BASE_URL ?>/dashboard.php" class="sidebar-logo">
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

            <li>
                <a href="<?= BASE_URL ?>/products/index.php">
                    <iconify-icon icon="hugeicons:package-box" class="menu-icon"></iconify-icon>
                    <span>Products</span>
                </a>
            </li>

            <li>
    <a href="<?= BASE_URL ?>/orders/index.php">
        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
        <span>Orders</span>
    </a>
</li>

            <li>
                <a href="<?= BASE_URL ?>/user/index.php">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">System</li>

            <li>
                <a href="<?= BASE_URL ?>/settings/index.php">
                    <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                    <span>Settings</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/logout.php" class="text-danger-600">
                    <iconify-icon icon="lucide:power" class="menu-icon text-danger-600"></iconify-icon>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
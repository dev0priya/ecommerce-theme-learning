<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    
    <div>
        <a href="<?= BASE_URL ?>/modules/user/dashboard.php" class="sidebar-logo">
            <img src="<?= BASE_URL ?>/assets/uploads/<?= getSetting('site_logo', $pdo); ?>" 
                 class="light-logo w-10 h-10">
            <span class="text-xl font-bold text-neutral-900 ms-2">User Panel</span>
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            <!-- ================= ACCOUNT ================= -->
            <li class="sidebar-menu-group-title">My Account</li>

            <li>
                <a href="<?= BASE_URL ?>/modules/user/dashboard.php">
                    <iconify-icon icon="solar:pie-chart-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/modules/user/orders.php">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>My Orders</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/modules/user/address.php">
                    <iconify-icon icon="mdi:map-marker-outline" class="menu-icon"></iconify-icon>
                    <span>Addresses</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/modules/user/security.php">
                    <iconify-icon icon="solar:lock-password-outline" class="menu-icon"></iconify-icon>
                    <span>Security</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/modules/user/edit-profile.php">
                    <iconify-icon icon="solar:user-linear" class="menu-icon"></iconify-icon>
                    <span>Edit Profile</span>
                </a>
            </li>

            <!-- ================= SHOP ================= -->
            <li class="sidebar-menu-group-title">Shopping</li>

            <li>
                <a href="<?= BASE_URL ?>/index.php">
                    <iconify-icon icon="mdi:shopping-outline" class="menu-icon"></iconify-icon>
                    <span>Shop</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/cart/index.php">
                    <iconify-icon icon="mdi:cart-outline" class="menu-icon"></iconify-icon>
                    <span>My Cart</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>/pages/wishlist.php">
                    <iconify-icon icon="mdi:heart-outline" class="menu-icon"></iconify-icon>
                    <span>Wishlist</span>
                </a>
            </li>

            <!-- ================= SUPPORT ================= -->
            <li class="sidebar-menu-group-title">Support</li>

            <li>
                <a href="<?= BASE_URL ?>/contact.php">
                    <iconify-icon icon="mdi:help-circle-outline" class="menu-icon"></iconify-icon>
                    <span>Help Center</span>
                </a>
            </li>

            <!-- ================= LOGOUT ================= -->
            <li>
                <a href="<?= BASE_URL ?>/logout.php" class="text-danger-600">
                    <iconify-icon icon="lucide:power" class="menu-icon text-danger-600"></iconify-icon>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
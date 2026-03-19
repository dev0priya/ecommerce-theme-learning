<div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
    <div class="flex items-center justify-between">
        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-[16px]">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                </button>
                <h6 class="mb-0 text-lg font-semibold text-neutral-900 dark:text-white"><?= $title ?? 'Dashboard' ?></h6>
            </div>
        </div>
        <div class="col-auto">
            <div class="flex items-center gap-3">
                <button data-dropdown-toggle="dropdownProfile" class="flex justify-center items-center rounded-full" type="button">
                    <img src="<?= BASE_URL ?>assets/images/user.png" alt="image" class="w-10 h-10 object-fit-cover rounded-full">
                </button>
                <div id="dropdownProfile" class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
                    <div class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between">
                        <div>
                            <h6 class="text-sm text-neutral-900 font-semibold mb-0">Admin User</h6>
                            <span class="text-xs text-neutral-500">Administrator</span>
                        </div>
                    </div>
                    <ul class="flex flex-col">
                        <li><a class="text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4" href="<?= BASE_URL ?>logout.php">
                            <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require '../include/load.php';
checkLogin();

// 1. Fetch all users from Database (Project A Logic)
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include '../partials/header.php';
?>

<div class="flex min-h-screen">
    <?php 
    // Automated Sidebar selection
    if ($_SESSION['user_role'] === 'admin') {
        include '../partials/sidebar-admin.php';
    } else {
        include '../partials/sidebar-user.php';
    }
    ?>

    <main class="dashboard-main flex-grow-1">
        <div class="dashboard-main-body p-6">
            
            <div class="card-header border-b border-neutral-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-4 px-6 flex items-center flex-wrap gap-3 justify-between rounded-t-xl">
                <div class="flex items-center flex-wrap gap-3">
                    <h5 class="text-xl font-bold text-neutral-900 dark:text-white mb-0">Manage Users</h5>
                </div>
                <a href="add.php" class="btn btn-primary text-sm btn-sm px-3 py-3 rounded-lg flex items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Add New User
                </a>
            </div>

            <div class="card h-full p-0 rounded-b-xl border-0 overflow-hidden shadow-sm">
                <div class="card-body p-6">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">User Info</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="text-center">Role</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full shrink-0 me-2 bg-primary-100 flex items-center justify-center text-primary-600 font-bold">
                                                <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                            </div>
                                            <div class="grow">
                                                <span class="text-base mb-0 font-semibold text-neutral-800 dark:text-white"><?= e($u['name']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary-light"><?= e($u['email']) ?></span></td>
                                    <td class="text-center">
                                        <?php 
                                            $roleClass = ($u['role'] === 'admin') ? 'bg-purple-100 text-purple-600 border-purple-600' : 'bg-success-100 text-success-600 border-success-600';
                                        ?>
                                        <span class="<?= $roleClass ?> border px-4 py-1 rounded-full font-medium text-xs">
                                            <?= ucfirst(e($u['role'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center gap-3 justify-center">
                                            <a href="edit.php?id=<?= $u['id'] ?>" 
                                               class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 bg-hover-success-200 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                <iconify-icon icon="lucide:edit" class="icon text-xl"></iconify-icon>
                                            </a>
                                            <button type="button" onclick="deleteItem(<?= $u['id'] ?>, 'users')"
                                                    class="bg-danger-100 dark:bg-danger-600/25 hover:bg-danger-200 text-danger-600 dark:text-danger-500 font-medium w-10 h-10 flex justify-center items-center rounded-full">
                                                <iconify-icon icon="fluent:delete-24-regular" class="icon text-xl"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <?php include '../partials/footer.php'; ?>
    </main>
</div>
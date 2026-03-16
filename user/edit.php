<?php
require '../include/load.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) { redirect('index.php'); }

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) { die("User not found."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];

    if (!empty($_POST['password'])) {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?";
        $params = [$name, $email, $role, $hashed_pass, $id];
    } else {
        $sql = "UPDATE users SET name=?, email=?, role=? WHERE id=?";
        $params = [$name, $email, $role, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    redirect('index.php');
}

include '../partials/header.php';
?>

<div class="flex min-h-screen">
    <?php include '../partials/sidebar-admin.php'; ?>

    <main class="dashboard-main flex-grow-1">
        <div class="dashboard-main-body p-6">
            
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h6 class="text-2xl font-bold text-neutral-900 dark:text-white mb-0">Edit User Profile</h6>
                <a href="index.php" class="btn btn-outline-primary-600 px-6 py-2 rounded-lg flex items-center gap-2">
                    <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
                    Back to List
                </a>
            </div>

            <div class="card h-full p-0 rounded-xl border-0 overflow-hidden shadow-sm">
                <div class="card-body p-6">
                    <form method="POST">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            <div class="lg:col-span-4 flex flex-col items-center border-r border-neutral-100 dark:border-neutral-600 pr-6">
                                <h6 class="text-base text-neutral-600 dark:text-neutral-200 mb-6 w-full text-center">User Avatar</h6>
                                <div class="avatar-upload relative">
                                    <div class="avatar-preview w-40 h-40 rounded-full border-4 border-white shadow-md bg-primary-100 flex items-center justify-center text-5xl font-bold text-primary-600 overflow-hidden">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div class="absolute bottom-2 right-2">
                                        <label for="imageUpload" class="w-10 h-10 flex justify-center items-center bg-primary-600 text-white rounded-full cursor-pointer shadow-lg hover:bg-primary-700 transition-all border-2 border-white">
                                            <iconify-icon icon="solar:camera-outline"></iconify-icon>
                                        </label>
                                        <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                    </div>
                                </div>
                                <span class="mt-4 px-3 py-1 bg-neutral-100 dark:bg-neutral-800 rounded text-xs text-neutral-500 italic">Avatar uploads are currently UI-only</span>
                            </div>

                            <div class="lg:col-span-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="col-span-1">
                                        <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Full Name</label>
                                        <input type="text" name="name" value="<?= e($user['name']) ?>" class="form-control rounded-lg" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Email</label>
                                        <input type="email" name="email" value="<?= e($user['email']) ?>" class="form-control rounded-lg" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Account Role</label>
                                        <select name="role" class="form-control rounded-lg form-select">
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Customer</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="inline-block font-semibold text-warning-600 text-sm mb-2">Update Password</label>
                                        <input type="password" name="password" class="form-control rounded-lg border-warning-200" placeholder="Leave blank to keep current">
                                    </div>
                                </div>

                                <div class="flex items-center justify-start gap-3 mt-10 border-t border-neutral-100 pt-6">
                                    <button type="submit" class="btn btn-primary-600 px-14 py-3 rounded-lg shadow-sm">
                                        Save Changes
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include '../partials/footer.php'; ?>
    </main>
</div>
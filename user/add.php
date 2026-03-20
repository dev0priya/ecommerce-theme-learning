<?php
require '../include/load.php';
checkLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];
    $pass  = $_POST['password'];

    if (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_pass, $role]);
            redirect('index.php');
        } catch (PDOException $e) {
            $error = "Error: Email might already exist.";
        }
    }
}

include '../partials/header.php';
?>

<div class="flex min-h-screen">
    <?php include '../partials/sidebar-admin.php'; ?>

    <main class="dashboard-main flex-grow-1">
        <div class="dashboard-main-body p-6">
            
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h6 class="text-2xl font-bold text-neutral-900 dark:text-white mb-0">Add New User</h6>
                <a href="index.php" class="btn btn-outline-primary-600 px-6 py-2 rounded-lg flex items-center gap-2">
                    <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
                    Back to List
                </a>
            </div>

            <div class="card h-full p-0 rounded-xl border-0 overflow-hidden shadow-sm">
                <div class="card-body p-6">
                    <?php if ($error): ?>
                        <div class="alert alert-danger bg-danger-100 text-danger-600 border border-danger-600 mb-4 p-3 rounded-lg flex items-center gap-2">
                            <iconify-icon icon="solar:danger-circle-bold"></iconify-icon>
                            <?= e($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Full Name <span class="text-danger-600">*</span></label>
                                <input type="text" name="name" class="form-control rounded-lg" placeholder="Enter Full Name" required>
                            </div>

                            <div class="col-span-1">
                                <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Email Address <span class="text-danger-600">*</span></label>
                                <input type="email" name="email" class="form-control rounded-lg" placeholder="Enter Email" required>
                            </div>

                            <div class="col-span-1">
                                <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Password <span class="text-danger-600">*</span></label>
                                <input type="password" name="password" class="form-control rounded-lg" placeholder="At least 6 characters" required>
                            </div>

                            <div class="col-span-1">
                                <label class="inline-block font-semibold text-neutral-600 dark:text-neutral-200 text-sm mb-2">Assign Role <span class="text-danger-600">*</span></label>
                                <select name="role" class="form-control rounded-lg form-select">
                                    <option value="user">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-center gap-3 mt-10">
                            <button type="reset" class="border border-neutral-300 bg-neutral-100 text-neutral-600 text-base px-10 py-3 rounded-lg hover:bg-neutral-200 transition-all">
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary-600 border border-primary-600 text-base px-14 py-3 rounded-lg shadow-sm">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include '../partials/footer.php'; ?>
    </main>
</div>
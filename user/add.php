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

$title = 'Register New Member';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Adaptive Background */
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Premium Create Card */
    .create-user-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
    }
    .dark .create-user-card { background: #0f172a; border-color: #1e293b; }

    /* Form Labels & Inputs */
    .field-label {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .dark .field-label { color: #94a3b8; }

    .input-pop-styled {
        width: 100%;
        padding: 14px 20px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 16px;
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .input-pop-styled { background: #1e293b; color: #f1f5f9; }
    .input-pop-styled:focus {
        background: white;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15);
    }
    .dark .input-pop-styled:focus { background: #0f172a; }

    /* Custom Buttons */
    .btn-create-member {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        padding: 16px 48px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn-create-member:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5); }

    .btn-reset-minimal {
        background: #f1f5f9;
        color: #64748b;
        padding: 16px 30px;
        border-radius: 18px;
        font-weight: 800;
        font-size: 12px;
        transition: 0.3s;
        border: none;
    }
    .dark .btn-reset-minimal { background: #1e293b; color: #94a3b8; }
    .btn-reset-minimal:hover { background: #e2e8f0; color: #1e293b; }

    /* Top Navigation Button */
    .btn-back-minimal {
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .btn-back-minimal:hover { color: #6366f1; transform: translateX(-5px); }
</style>

<div class="dashboard-main-body px-6 py-10 lg:px-12">
    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-10">
            <div>
                <a href="index.php" class="btn-back-minimal mb-3">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="text-lg"></iconify-icon>
                    Back to Directory
                </a>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">New Registration</h1>
            </div>
            <div class="hidden md:block">
                <iconify-icon icon="solar:user-plus-bold-duotone" class="text-5xl text-indigo-600/20"></iconify-icon>
            </div>
        </div>

        <div class="create-user-card shadow-2xl">
            <?php if ($error): ?>
                <div class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-500">
                    <iconify-icon icon="solar:danger-circle-bold" class="text-xl"></iconify-icon>
                    <span class="text-sm font-bold"><?= htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    
                    <div class="col-span-1">
                        <label class="field-label">Full Legal Name</label>
                        <div class="relative">
                            <iconify-icon icon="solar:user-bold-duotone" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></iconify-icon>
                            <input type="text" name="name" class="input-pop-styled pl-12" placeholder="e.g. Rahul Sharma" required>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">Email Address</label>
                        <div class="relative">
                            <iconify-icon icon="solar:letter-bold-duotone" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></iconify-icon>
                            <input type="email" name="email" class="input-pop-styled pl-12" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">System Role</label>
                        <div class="relative">
                            <iconify-icon icon="solar:shield-user-bold-duotone" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></iconify-icon>
                            <select name="role" class="input-pop-styled pl-12 appearance-none">
                                <option value="user">Customer Account</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">Access Password</label>
                        <div class="relative">
                            <iconify-icon icon="solar:lock-password-bold-duotone" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></iconify-icon>
                            <input type="password" name="password" class="input-pop-styled pl-12" placeholder="••••••••" required>
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-2 px-1">Minimum 6 characters required</p>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <button type="reset" class="btn-reset-minimal w-full sm:w-auto">
                        Clear Fields
                    </button>
                    <button type="submit" class="btn-create-member w-full sm:w-auto">
                        Finalize Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
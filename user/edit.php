<?php
require '../include/load.php';
checkLogin();

$id = $_GET['id'] ?? null;
if (!$id) { redirect('index.php'); }

// Fetch User Data using PDO
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

$title = 'Edit Profile: ' . e($user['name']);
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Adaptive Background */
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Premium Profile Card */
    .profile-edit-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
    }
    .dark .profile-edit-card { background: #0f172a; border-color: #1e293b; }

    /* Avatar Design */
    .profile-avatar-big {
        width: 160px;
        height: 160px;
        border-radius: 44px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        font-weight: 900;
        color: white;
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.4);
    }

    /* --- CAMERA ICON VISIBILITY FIX --- */
    .avatar-edit-btn {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
        border: 4px solid #f8fafc; /* Match light bg */
        color: #4f46e5;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* Dark Mode: Background dark indigo aur border blackish */
    .dark .avatar-edit-btn {
        background: #4f46e5; /* Indigo background for pop */
        color: #ffffff;      /* White icon */
        border-color: #0f172a; /* Match dark card/bg */
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
    }

    .avatar-edit-btn:hover {
        transform: scale(1.1) rotate(5deg);
        background: #6366f1;
        color: white;
    }

    /* Form Labels & Inputs */
    .edit-label {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .dark .edit-label { color: #94a3b8; }

    .edit-input-styled {
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
    .dark .edit-input-styled { background: #1e293b; color: #f1f5f9; }
    .edit-input-styled:focus {
        background: white;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15);
    }
    .dark .edit-input-styled:focus { background: #0f172a; }

    /* Custom Save Button */
    .btn-save-profile {
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
    .btn-save-profile:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5); }

    .btn-back-minimal {
        font-size: 11px; font-weight: 900; text-transform: uppercase;
        letter-spacing: 1px; color: #94a3b8; display: flex;
        align-items: center; gap: 8px; transition: 0.3s;
    }
    .btn-back-minimal:hover { color: #6366f1; transform: translateX(-5px); }
</style>

<div class="dashboard-main-body px-6 py-10 lg:px-12">
    <div class="max-w-5xl mx-auto">
        
        <div class="flex items-center justify-between mb-10">
            <div>
                <a href="index.php" class="btn-back-minimal mb-3">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="text-lg"></iconify-icon>
                    Back to Directory
                </a>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Edit Account</h1>
            </div>
        </div>

        <div class="profile-edit-card shadow-2xl">
            <form method="POST">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    
                    <div class="lg:col-span-4 flex flex-col items-center">
                        <div class="relative">
                            <div class="profile-avatar-big">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <label for="imageUpload" class="avatar-edit-btn">
                                <iconify-icon icon="solar:camera-bold-duotone" class="text-2xl"></iconify-icon>
                                <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                            </label>
                        </div>
                        <div class="mt-8 text-center">
                            <h3 class="text-xl font-black text-slate-800 dark:text-white"><?= e($user['name']) ?></h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1"><?= ucfirst($user['role']) ?> Account</p>
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                            <div class="col-span-1">
                                <label class="edit-label">Full Legal Name</label>
                                <input type="text" name="name" value="<?= e($user['name']) ?>" class="edit-input-styled" required>
                            </div>
                            <div class="col-span-1">
                                <label class="edit-label">Email Address</label>
                                <input type="email" name="email" value="<?= e($user['email']) ?>" class="edit-input-styled" required>
                            </div>
                            <div class="col-span-1">
                                <label class="edit-label">Administrative Role</label>
                                <select name="role" class="edit-input-styled appearance-none">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Customer Account</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="edit-label">Update Security Password</label>
                                <input type="password" name="password" class="edit-input-styled border-amber-200/50 dark:border-amber-900/30" placeholder="••••••••">
                                <p class="text-[9px] font-bold text-amber-500 uppercase mt-2">
                                    <iconify-icon icon="solar:shield-warning-bold"></iconify-icon> Leave blank to keep current
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-start gap-4 pt-8 border-t border-slate-100 dark:border-slate-800">
                            <button type="submit" class="btn-save-profile">
                                Save Profile
                            </button>
                            <a href="index.php" class="text-xs font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest transition-all">
                                Cancel Changes
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
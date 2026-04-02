<?php
require 'include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Get current data from ecommerce_db
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $gender  = $_POST['gender'] ?? null;
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $bio     = trim($_POST['bio']);
    $avatarName = $user['avatar'];

    // If new image uploaded to assets/avatars/
    if (!empty($_FILES['avatar']['name'])) {
        $upload = uploadImage($_FILES['avatar'], 'avatars');
        if ($upload) {
            // Purani image delete karne ka logic (optional)
            if ($user['avatar'] && file_exists('assets/avatars/' . $user['avatar'])) {
                unlink('assets/avatars/' . $user['avatar']);
            }
            $avatarName = $upload;
        }
    }

    // Updating all fields including new ones
    $stmt = $pdo->prepare("
        UPDATE users 
        SET name = ?, gender = ?, avatar = ?, phone = ?, address = ?, bio = ?
        WHERE id = ?
    ");

    if ($stmt->execute([$name, $gender, $avatarName, $phone, $address, $bio, $userId])) {
        $success = "Profile updated successfully! Redirecting...";
        header("refresh:2;url=view-myprofile.php");
    } else {
        $error = "Something went wrong. Please try again.";
    }
}

$title = 'Edit Profile - Project A';
include 'partials/layouts/layoutTop.php'; 
?>

<style>
    .edit-profile-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; padding-bottom: 50px; }
    .dark .edit-profile-body { background: #020617; }

    .edit-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
    }
    .dark .edit-card { background: #0f172a; border-color: #1e293b; }

    .input-label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }
    .dark .input-label { color: #f1f5f9; }

    .form-control-premium {
        width: 100%;
        padding: 14px 20px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 16px;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .form-control-premium { background: #020617; color: #ffffff; border-color: #334155; }
    .form-control-premium:focus { border-color: #6366f1; outline: none; background: white; }
    .dark .form-control-premium:focus { background: #0f172a; }

    .avatar-preview-box {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        border: 4px solid #f1f5f9;
        overflow: hidden;
        background: #fff;
    }
    .dark .avatar-preview-box { border-color: #1e293b; }

    .btn-update-profile {
        background: #6366f1;
        color: white !important;
        padding: 16px 32px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        transition: 0.3s;
    }
    .btn-update-profile:hover { transform: translateY(-3px); background: #4f46e5; }
</style>

<div class="edit-profile-body px-6 py-10">
    <div class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Edit Profile</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[3px]">Manage your personal data</p>
            </div>
            <a href="view-myprofile.php" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline flex items-center gap-2">
                <iconify-icon icon="solar:alt-arrow-left-bold"></iconify-icon> View Profile
            </a>
        </div>

        <?php if($success): ?>
            <div class="mb-6 p-4 bg-emerald-500/10 text-emerald-500 rounded-2xl font-bold text-sm border border-emerald-500/20 flex items-center gap-3">
                <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon> <?= $success ?>
            </div>
        <?php endif; ?>

        <div class="edit-card shadow-xl">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="flex items-center gap-8 mb-10 pb-10 border-b border-slate-100 dark:border-slate-800">
                    <div class="avatar-preview-box shadow-md">
                        <img src="assets/avatars/<?= $user['avatar'] ?: 'default-user.png' ?>" class="w-full h-full object-cover" id="previewImg">
                    </div>
                    <div>
                        <label class="input-label">Profile Picture</label>
                        <input type="file" name="avatar" class="text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer" onchange="previewFile(this)">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-wider">Recommended: Square JPG/PNG (Max 2MB)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-1">
                        <label class="input-label">Full Name</label>
                        <input type="text" name="name" value="<?= e($user['name']) ?>" class="form-control-premium" required>
                    </div>

                    <div class="col-span-1">
                        <label class="input-label">Gender</label>
                        <select name="gender" class="form-control-premium appearance-none">
                            <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label class="input-label">Phone Number</label>
                        <input type="text" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="form-control-premium" placeholder="+91 XXXX XXX XXX">
                    </div>

                    <div class="col-span-1">
                        <label class="input-label">Short Bio</label>
                        <input type="text" name="bio" value="<?= e($user['bio'] ?? '') ?>" class="form-control-premium" placeholder="Tell us about yourself">
                    </div>

                    <div class="col-span-2">
                        <label class="input-label">Full Residential Address</label>
                        <textarea name="address" class="form-control-premium" rows="3" placeholder="Enter your full address here..."><?= e($user['address'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button type="submit" class="btn-update-profile flex items-center gap-3">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="text-xl"></iconify-icon>
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Live Image Preview
    function previewFile(input) {
        var file = $("input[type=file]").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                $("#previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<?php include 'partials/layouts/layoutBottom.php'; ?>
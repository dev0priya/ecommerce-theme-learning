<?php 
    require 'include/load.php'; 
    checkLogin(); 

    // Login user ki ID session se uthana
    $current_user_id = $_SESSION['user_id']; 

    // Database ecommerce_db se data fetch karna
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$current_user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        die("User session not found. Please login again.");
    }

    $title = 'My Profile';
    include 'partials/layouts/layoutTop.php'; 
?>

<style>
    /* Premium Profile Design */
    .profile-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .profile-body { background: #020617; }

    .profile-header-card {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 32px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
    }

    .profile-img-box {
        width: 120px;
        height: 120px;
        border-radius: 30px;
        border: 4px solid rgba(255,255,255,0.2);
        background: #fff;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 32px;
        transition: 0.3s;
    }
    .dark .info-card { background: #0f172a; border-color: #1e293b; }

    .detail-tag {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #94a3b8;
        display: block;
        margin-bottom: 6px;
    }
    .detail-data {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }
    .dark .detail-data { color: #f1f5f9; }

    .btn-edit-glass {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white !important;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 900;
        font-size: 11px;
        text-transform: uppercase;
        transition: 0.3s;
    }
    .btn-edit-glass:hover { background: white; color: #6366f1 !important; }
</style>

<div class="profile-body px-6 py-10">
    <div class="max-w-5xl mx-auto">
        
        <div class="profile-header-card flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="profile-img-box shadow-xl">
                    <?php 
                        // Path updated to 'assets/avatars/' as per your request
                        $avatar_name = $user['avatar']; 
                        $avatar_path = 'assets/avatars/' . $avatar_name;
                        
                        // Check if file exists in the avatar folder
                        if (!empty($avatar_name) && file_exists($avatar_path)) {
                            $display_img = $avatar_path;
                        } else {
                            // Default image agar user ne upload nahi ki
                            $display_img = 'assets/images/default-user.png';
                        }
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover" alt="Profile Picture">
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter italic">
                        <?= e($user['name'] ?? 'User') ?>
                    </h1>
                    <p class="text-indigo-100 font-bold text-xs uppercase tracking-[4px] opacity-80 mt-1">
                        <?= e($user['role'] ?? 'Administrator') ?> • Project A
                    </p>
                </div>
            </div>
            <a href="edit-profile.php" class="btn-edit-glass">
                <iconify-icon icon="solar:pen-new-square-bold" class="mr-2 text-lg"></iconify-icon> Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-8">
                <div class="info-card shadow-sm border-t-4 border-t-indigo-500">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-10 flex items-center gap-3">
                        <iconify-icon icon="solar:user-id-bold-duotone" class="text-indigo-600 text-3xl"></iconify-icon>
                        Profile Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 gap-x-8">
                        <div>
                            <span class="detail-tag">Full Name</span>
                            <span class="detail-data"><?= e($user['name']) ?></span>
                        </div>
                        <div>
                            <span class="detail-tag">Email Address</span>
                            <span class="detail-data"><?= e($user['email']) ?></span>
                        </div>
                        <div>
                            <span class="detail-tag">Gender</span>
                            <span class="detail-data"><?= e($user['gender'] ?? 'Not Specified') ?></span>
                        </div>
                        <div>
                            <span class="detail-tag">Phone</span>
                            <span class="detail-data"><?= e($user['phone'] ?? 'Update Required') ?></span>
                        </div>
                        <div class="col-span-full">
                            <span class="detail-tag">Residential Address</span>
                            <span class="detail-data leading-relaxed">
                                <?= nl2br(e($user['address'] ?? 'No address found in ecommerce_db')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="info-card shadow-sm mb-8">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8">Account Status</h3>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl">
                            <iconify-icon icon="solar:shield-up-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <span class="text-sm font-black text-slate-800 dark:text-white block">Status: Active</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Verified Member</span>
                        </div>
                    </div>

                    <a href="security.php" class="w-full py-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl text-[11px] font-black text-slate-500 hover:text-indigo-600 dark:text-slate-400 uppercase tracking-[2px] text-center block transition-all border border-transparent hover:border-indigo-500">
                        Security Settings
                    </a>
                </div>

                <div class="info-card shadow-sm bg-slate-50/50 dark:bg-slate-800/20 border-dashed">
                    <span class="detail-tag">Member Since</span>
                    <span class="detail-data text-sm">
                        <?= ($user['created_at'] != '0000-00-00 00:00:00') ? date('M d, Y', strtotime($user['created_at'])) : 'Alpha Member' ?>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'partials/layouts/layoutBottom.php'; ?>
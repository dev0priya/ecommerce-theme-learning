<?php
require '../../include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];
$success = "";
$error = "";

// --- 1. Fetch Current User Data ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// --- 2. Handle Profile Update Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    if (!empty($name) && !empty($email)) {
        $update = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        if ($update->execute([$name, $email, $phone, $userId])) {
            $success = "Identity Synced Successfully!";
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone'] = $phone;
        }
    } else { $error = "Required: Name & Email"; }
}

include '../../partials/head.php';
?>

<?php include '../../partials/header.php'; ?>

<style>
    /* 1. Cyber-Atmosphere Layout */
    .user-panel-wrapper {
        display: flex;
        min-height: 100vh;
        background: #fcfcfc;
        position: relative;
        overflow: hidden;
    }
    .dark .user-panel-wrapper { 
        background: radial-gradient(circle at 0% 0%, #0f172a 0%, #020617 100%); 
    }

    /* Floating Neon Aura */
    .neon-aura-blob {
        position: absolute;
        width: 600px;
        height: 600px;
        filter: blur(150px);
        border-radius: 50%;
        z-index: 0;
        opacity: 0.08;
        pointer-events: none;
    }
    .aura-1 { top: -20%; right: -10%; background: #6366f1; }
    .aura-2 { bottom: -20%; left: -10%; background: #06b6d4; }

    .main-content-area {
        flex: 1;
        margin-left: 280px; 
        display: flex;
        flex-direction: column;
        z-index: 1;
        padding: 0 !important;
    }

    .content-body { padding: 60px 40px; flex-grow: 1; }

    /* 2. Premium Typography */
    .hero-title { font-size: 32px; font-weight: 950; color: #0f172a; letter-spacing: -2px; }
    .dark .hero-title { color: #ffffff !important; text-shadow: 0 0 20px rgba(255,255,255,0.1); }
    
    .breadcrumb-neon { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #6366f1; letter-spacing: 5px; }

    /* 3. The "Elite" Glass Card */
    .elite-profile-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border-radius: 40px;
        padding: 50px;
        border: 1px solid rgba(226, 232, 240, 0.5);
        box-shadow: 0 25px 60px rgba(0,0,0,0.02);
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 60px;
    }
    .dark .elite-profile-card { 
        background: rgba(15, 23, 42, 0.3); 
        border-color: rgba(99, 102, 241, 0.15); 
    }

    /* Floating Avatar */
    .avatar-vault {
        width: 140px;
        height: 140px;
        border-radius: 45px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 55px;
        font-weight: 950;
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
        margin-bottom: 30px;
        position: relative;
    }

    /* 4. INPUT VISIBILITY FIX */
    .input-label-neon { font-size: 10px; font-weight: 950; text-transform: uppercase; color: #475569; letter-spacing: 2px; margin-bottom: 12px; display: block; }
    .dark .input-label-neon { color: #cbd5e1 !important; }

    .input-cyber {
        width: 100%;
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        padding: 16px 22px;
        border-radius: 18px;
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        transition: 0.4s;
    }
    .dark .input-cyber { background: #020617; border-color: #1e293b; color: #fff; }

    /* FOCUS STATE: White Background + Black Text for Visibility */
    .input-cyber:focus, 
    .dark .input-cyber:focus { 
        background: #ffffff !important; 
        color: #000000 !important; /* Ab text kaala dikhega black theme mein bhi */
        border-color: #6366f1 !important; 
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.2);
        outline: none;
    }

    /* 5. Action Button & Footer */
    .btn-cyber-sync {
        background: #0f172a;
        color: white !important;
        padding: 18px 45px;
        border-radius: 20px;
        font-weight: 950;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 3px;
        border: none;
        transition: 0.4s;
    }
    .dark .btn-cyber-sync { background: linear-gradient(90deg, #6366f1, #a855f7); }
    .btn-cyber-sync:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(99, 102, 241, 0.4); }

    .footer-no-gap { width: 100%; margin-top: auto; border-top: 1px solid #f1f5f9; background: #fff; }
    .dark .footer-no-gap { background: #020617; border-color: #1e293b; }

    @media (max-width: 1100px) { .elite-profile-card { grid-template-columns: 1fr; padding: 30px; } .main-content-area { margin-left: 0 !important; width: 100%; } }
</style>

<div class="user-panel-wrapper">
    <div class="neon-aura-blob aura-1"></div>
    <div class="neon-aura-blob aura-2"></div>
    
    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-14">
                <span class="breadcrumb-neon">Personal Identity / Project A</span>
                <h1 class="hero-title mt-2 italic">Profile Configuration</h1>
            </div>

            <div class="elite-profile-card shadow-2xl">
                
                <div class="flex flex-col items-center lg:items-start text-center lg:text-left border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 pb-12 lg:pb-0 lg:pr-12">
                    <div class="avatar-vault">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter"><?= e($user['name']) ?></h4>
                    <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mt-2 mb-8"><?= e($user['email']) ?></p>
                    
                    <div class="w-full">
                        <div class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Status</span>
                            <div class="text-emerald-500 font-black text-[10px] uppercase">Verified User</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-10">
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        <div>
                            <label class="input-label-neon">Full Identity Name</label>
                            <input type="text" name="name" class="input-cyber" value="<?= e($user['name']) ?>" required>
                        </div>
                        <div>
                            <label class="input-label-neon">Primary Email</label>
                            <input type="email" name="email" class="input-cyber" value="<?= e($user['email']) ?>" required>
                        </div>
                        <div>
                            <label class="input-label-neon">Contact Link</label>
                            <input type="text" name="phone" class="input-cyber" value="<?= e($user['phone'] ?? '+91 ') ?>">
                        </div>
                        <div>
                            <label class="input-label-neon">Account Tier</label>
                            <input type="text" class="input-cyber opacity-40 cursor-not-allowed" value="Priority User" readonly>
                        </div>

                        <div class="md:col-span-2 pt-10 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest max-w-xs leading-relaxed">
                                <iconify-icon icon="solar:info-square-bold-duotone" class="text-indigo-500 text-lg mr-2 align-middle"></iconify-icon>
                                Synchronizing data will update your profile across Project A modules.
                            </p>
                            <button type="submit" name="update_profile" class="btn-cyber-sync">
                                Sync Profile
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

        <div class="footer-no-gap">
            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php
require '../../include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];
$success = "";
$error = "";

// --- Handle Password Change Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hashed, $userId]);
            $success = "Security credentials updated!";
        } else { $error = "Passwords mismatch."; }
    } else { $error = "Invalid current password."; }
}

include '../../partials/head.php';
?>

<?php include '../../partials/header.php'; ?>

<style>
    /* 1. Global Reset & Layout */
    .user-panel-wrapper { display: flex; min-height: 100vh; background: #f8fafc; position: relative; }
    .dark .user-panel-wrapper { background: #020617; }

    .main-content-area { flex: 1; margin-left: 280px; display: flex; flex-direction: column; padding: 0 !important; }
    .content-body { padding: 40px; flex-grow: 1; }

    /* 2. HEADING VISIBILITY FIX (Black Theme Priority) */
    .page-title { font-size: 26px; font-weight: 950; color: #1e293b; letter-spacing: -1.5px; }
    .dark .page-title { color: #ffffff !important; text-shadow: 0 0 15px rgba(255,255,255,0.1); }
    
    .section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 4px; }
    .dark .section-label { color: #94a3b8 !important; }

    /* 3. Premium Security Cards */
    .sec-glass-card {
        background: white;
        border-radius: 28px;
        padding: 35px;
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
        transition: 0.3s;
    }
    .dark .sec-glass-card { background: rgba(15, 23, 42, 0.4); border-color: rgba(99, 102, 241, 0.2); backdrop-filter: blur(10px); }

    /* Field Labels Visibility */
    .field-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #475569; letter-spacing: 1px; display: block; margin-bottom: 10px; }
    .dark .field-label { color: #cbd5e1 !important; }

    .input-premium-neon {
        width: 100%;
        background: #f1f5f9;
        border: 1px solid transparent;
        padding: 14px 20px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 14px;
        transition: 0.3s;
    }
    .dark .input-premium-neon { background: #020617; border-color: #1e293b; color: #fff; }
    .input-premium-neon:focus { border-color: #6366f1; background: #fff; outline: none; box-shadow: 0 0 15px rgba(99, 102, 241, 0.1); }

    /* 4. Icon & Button Visibility */
    .icon-glow-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #6366f1;
    }
    .dark .icon-glow-box { background: rgba(99, 102, 241, 0.15); color: #a5b4fc !important; }

    .btn-neon-save {
        background: #6366f1;
        color: white !important;
        padding: 15px 35px;
        border-radius: 16px;
        font-weight: 950;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 2px;
        border: none;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        transition: 0.3s;
    }
    .btn-neon-save:hover { transform: translateY(-3px); background: #4f46e5; box-shadow: 0 15px 35px rgba(99, 102, 241, 0.5); }

    /* Footer Seamless Connection */
    .footer-no-gap { width: 100%; margin-top: auto; border-top: 1px solid #e2e8f0; background: white; }
    .dark .footer-no-gap { background: #020617; border-color: #1e293b; }

    @media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-12 flex items-center justify-between">
                <div>
                    <span class="section-label">Account Fortress</span>
                    <h1 class="page-title mt-1 italic">Security Hub</h1>
                </div>
                <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 px-5 py-2 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    <iconify-icon icon="solar:shield-check-bold" class="text-emerald-500"></iconify-icon>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">System Secure</span>
                </div>
            </div>

            <div class="sec-glass-card shadow-sm">
                <div class="flex items-center gap-5 mb-10">
                    <div class="icon-glow-box">
                        <iconify-icon icon="solar:lock-keyhole-bold-duotone" class="text-2xl"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Authentication Shield</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Manage your access keys</p>
                    </div>
                </div>

                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label class="field-label">Current Access Key</label>
                        <input type="password" name="current_password" class="input-premium-neon" placeholder="••••••••" required>
                        
                        <div class="mt-8 p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase">
                                <iconify-icon icon="solar:info-circle-bold" class="mr-1"></iconify-icon>
                                Password must be at least 8 characters long and contain symbols.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between">
                        <div class="space-y-6">
                            <div>
                                <label class="field-label">New Access Key</label>
                                <input type="password" name="new_password" class="input-premium-neon" placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="field-label">Confirm New Key</label>
                                <input type="password" name="confirm_password" class="input-premium-neon" placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <div class="text-right mt-10">
                            <button type="submit" name="update_password" class="btn-neon-save">
                                Sync New Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="sec-glass-card border-l-4 border-l-cyan-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="icon-glow-box" style="color: #06b6d4; background: rgba(6,182,212,0.1);">
                                <iconify-icon icon="solar:smartphone-bold-duotone" class="text-2xl"></iconify-icon>
                            </div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">2-Step Verification</h4>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inactive</span>
                    </div>
                </div>

                <div class="sec-glass-card border-l-4 border-l-rose-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="icon-glow-box" style="color: #f43f5e; background: rgba(244,63,94,0.1);">
                                <iconify-icon icon="solar:user-block-bold-duotone" class="text-2xl"></iconify-icon>
                            </div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Account Status</h4>
                        </div>
                        <button class="text-[9px] font-black text-rose-500 uppercase tracking-widest hover:underline">Deactivate</button>
                    </div>
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
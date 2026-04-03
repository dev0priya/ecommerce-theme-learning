<?php
require '../../include/load.php';
checkLogin();

$userId = $_SESSION['user_id'];

// --- Handle New Address Insert ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
    $address = $_POST['address'];
    $city    = $_POST['city'];
    $state   = $_POST['state'];
    $zip     = $_POST['zip'];
    $default = isset($_POST['is_default']) ? 1 : 0;

    if($default) {
        $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    }

    $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, address, city, state, zip, is_default) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $address, $city, $state, $zip, $default]);
    header("Location: address.php?success=1");
    exit;
}

// Fetch all addresses for this user
$stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll();

include '../../partials/head.php';
?>

<?php include '../../partials/header.php'; ?>

<style>
    /* 1. Seamless Layout Reset */
    .user-panel-wrapper { display: flex; min-height: 100vh; background: #f8fafc; position: relative; }
    .dark .user-panel-wrapper { background: #020617; }

    .main-content-area { flex: 1; margin-left: 280px; display: flex; flex-direction: column; padding: 0 !important; }
    .content-body { padding: 40px; flex-grow: 1; }

    /* 2. HEADING VISIBILITY FIX (Specifically for Black Theme) */
    .page-title { font-size: 26px; font-weight: 900; color: #1e293b; letter-spacing: -1px; }
    .dark .page-title { color: #ffffff !important; } /* White for Black Theme */
    
    .section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 4px; }
    .dark .section-label { color: #94a3b8 !important; } /* Light Grey for Black Theme */

    /* 3. Compact 2-Column Form Box */
    .compact-form-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .dark .compact-form-card { background: rgba(15, 23, 42, 0.4); border-color: rgba(99, 102, 241, 0.2); }

    /* Label visibility inside form */
    .form-label-neon { font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 2px; display: block; margin-bottom: 8px; }
    .dark .form-label-neon { color: #cbd5e1 !important; }

    .input-neon-style {
        width: 100%;
        background: #f1f5f9;
        border: 1px solid transparent;
        padding: 12px 18px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 13px;
        transition: 0.3s;
    }
    .dark .input-neon-style { background: #020617; border-color: #1e293b; color: #fff; }
    .input-neon-style:focus { border-color: #6366f1; background: #fff; outline: none; }

    /* 4. Address Cards Grid */
    .addr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
    
    .addr-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }
    .dark .addr-card { background: rgba(15, 23, 42, 0.6); border-color: rgba(255,255,255,0.05); }
    .addr-card:hover { border-color: #6366f1; transform: translateY(-5px); }
    .is-default-neon { border-left: 4px solid #6366f1 !important; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.1); }

    /* Icon Visibility Fix */
    .addr-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #6366f1;
    }
    .dark .addr-icon { background: rgba(99, 102, 241, 0.1); color: #a5b4fc !important; }

    .btn-save-neon {
        background: #6366f1;
        color: white !important;
        padding: 14px 30px;
        border-radius: 14px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.5px;
        border: none;
        transition: 0.3s;
    }

    /* Footer Gap Removal */
    .footer-no-gap { width: 100%; margin-top: auto; border-top: 1px solid #e2e8f0; background: white; }
    .dark .footer-no-gap { background: #020617; border-color: #1e293b; }

    @media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="mb-10">
                <span class="section-label">Shipping Profile</span>
                <h1 class="page-title mt-1 italic">Saved Addresses</h1>
            </div>

            <div class="compact-form-card shadow-sm">
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <label class="form-label-neon">Full Street Address</label>
                            <input type="text" name="address" class="input-neon-style" placeholder="e.g. H-Block, Sindhi Colony" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-neon">City</label>
                                <input type="text" name="city" class="input-neon-style" required>
                            </div>
                            <div>
                                <label class="form-label-neon">State</label>
                                <input type="text" name="state" class="input-neon-style" required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 flex flex-col justify-between">
                        <div>
                            <label class="form-label-neon">Zip / Postal Code</label>
                            <input type="text" name="zip" class="input-neon-style" required>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_default" id="setDef" class="w-4 h-4 rounded">
                                <label for="setDef" class="text-xs font-bold text-slate-500">Set as Primary</label>
                            </div>
                            <button type="submit" name="save_address" class="btn-save-neon shadow-lg shadow-indigo-500/20">
                                Save Location
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="addr-grid">
                <?php foreach($addresses as $row): ?>
                <div class="addr-card shadow-sm <?= $row['is_default'] ? 'is-default-neon' : '' ?>">
                    <div class="flex items-center justify-between mb-5">
                        <div class="addr-icon">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-2xl"></iconify-icon>
                        </div>
                        <?php if($row['is_default']): ?>
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-lg">Primary</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-sm font-black text-slate-800 dark:text-white leading-relaxed mb-1">
                        <?= e($row['address']) ?>
                    </p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                        <?= e($row['city']) ?>, <?= e($row['state']) ?> - <?= e($row['zip']) ?>
                    </p>

                    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 flex gap-6">
                        <button class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Edit</button>
                        <button class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Delete</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="footer-no-gap">
            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

</body>
</html>
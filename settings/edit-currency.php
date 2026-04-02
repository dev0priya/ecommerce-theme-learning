<?php 
    require '../include/load.php'; 
    checkLogin(); 

    $id = $_GET['id'] ?? null;
    if (!$id) { redirect('currencies.php'); }

    // Fetching specific currency from ecommerce_db
    $stmt = $pdo->prepare("SELECT * FROM currencies WHERE id = ?");
    $stmt->execute([$id]);
    $currency = $stmt->fetch();

    if (!$currency) { die("Currency not found in ecommerce_db."); }

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name       = trim($_POST['name']);
        $symbol     = trim($_POST['symbol']);
        $code       = strtoupper(trim($_POST['code']));
        $is_crypto  = isset($_POST['is_crypto']) ? 1 : 0;
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        $status     = isset($_POST['status']) ? 1 : 0;

        try {
            // Agar ye default set ho raha hai, toh baki sabko non-default karna hoga
            if ($is_default) {
                $pdo->query("UPDATE currencies SET is_default = 0");
            }

            $update = $pdo->prepare("UPDATE currencies SET name=?, symbol=?, code=?, is_crypto=?, is_default=?, status=? WHERE id=?");
            $update->execute([$name, $symbol, $code, $is_crypto, $is_default, $status, $id]);
            
            $success = "Currency updated successfully!";
            // Redirect after 2 seconds to list page
            header("refresh:2;url=currencies.php");
        } catch (PDOException $e) {
            $error = "Error updating currency. Code might be duplicate.";
        }
    }

    $title = 'Edit Currency: ' . e($currency['name']);
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    .center-container { flex-grow: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

    .edit-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 45px;
        width: 100%;
        max-width: 800px;
        transition: 0.3s;
    }
    .dark .edit-card { background: #0f172a; border-color: #1e293b; }

    .field-label { display: block; font-size: 10px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 1.5px; margin-bottom: 10px; }
    .dark .field-label { color: #f1f5f9; }

    .input-styled {
        width: 100%; padding: 14px 20px; background: #f1f5f9; border: 2px solid transparent; border-radius: 16px;
        font-weight: 700; font-size: 14px; color: #1e293b; transition: 0.3s;
    }
    .dark .input-styled { background: #020617; color: #ffffff; border-color: #334155; }
    .input-styled:focus { border-color: #6366f1; outline: none; }

    .btn-update {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white !important; padding: 16px 40px; border-radius: 18px; font-weight: 900;
        text-transform: uppercase; font-size: 12px; letter-spacing: 1px; border: none; cursor: pointer; transition: 0.3s;
    }
    .btn-update:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4); }
</style>

<div class="dashboard-main-body">
    <div class="center-container">
        <div class="edit-card shadow-2xl">
            
            <div class="flex items-center justify-between mb-10">
                <div>
                    <a href="currencies.php" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest flex items-center gap-2 mb-2">
                        <iconify-icon icon="solar:alt-arrow-left-bold"></iconify-icon> Back to List
                    </a>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Edit Currency</h1>
                </div>
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 text-3xl font-black">
                    <?= e($currency['symbol']) ?>
                </div>
            </div>

            <?php if($error): ?>
                <div class="mb-6 p-4 bg-rose-500/10 text-rose-500 rounded-2xl font-bold text-sm border border-rose-500/20">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="mb-6 p-4 bg-emerald-500/10 text-emerald-500 rounded-2xl font-bold text-sm border border-emerald-500/20">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="col-span-1">
                        <label class="field-label">Currency Name</label>
                        <input type="text" name="name" value="<?= e($currency['name']) ?>" class="input-styled" required>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Currency Symbol</label>
                        <input type="text" name="symbol" value="<?= e($currency['symbol']) ?>" class="input-styled" required>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Currency Code (ISO)</label>
                        <input type="text" name="code" value="<?= e($currency['code']) ?>" class="input-styled" placeholder="USD, INR, etc." required>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Configurations</label>
                        <div class="flex flex-wrap gap-4 mt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_crypto" <?= $currency['is_crypto'] ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Cryptocurrency</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_default" <?= $currency['is_default'] ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Set as Default</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="status" <?= $currency['status'] ? 'checked' : '' ?> class="w-4 h-4 text-indigo-600">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Active Status</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-center">
                    <button type="submit" class="btn-update flex items-center gap-3">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="text-xl"></iconify-icon>
                        Update Currency Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
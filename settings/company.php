<?php 
    require '../include/load.php'; 
    checkLogin(); 

    // Fetching current company data from ecommerce_db
    $stmt = $pdo->prepare("SELECT * FROM company_settings WHERE id = 1");
    $stmt->execute();
    $company = $stmt->fetch();

    $success = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['company_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $website = $_POST['website'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip_code'];
        $address = $_POST['address'];

        $update = $pdo->prepare("UPDATE company_settings SET company_name=?, email=?, phone=?, website=?, country=?, city=?, state=?, zip_code=?, address=? WHERE id=1");
        $update->execute([$name, $email, $phone, $website, $country, $city, $state, $zip, $address]);
        $success = "Company settings updated successfully!";
        header("refresh:1;url=company.php");
    }

    $title = 'Company Settings';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    .company-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; padding-bottom: 60px; }
    .dark .company-body { background: #020617; }

    /* Card Design */
    .settings-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
    }
    .dark .settings-card { background: #0f172a; border-color: #1e293b; }

    /* Input Labels Visibility Fix */
    .field-label {
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #64748b;
        margin-bottom: 10px;
        display: block;
    }
    .dark .field-label { color: #f1f5f9; }

    /* Inputs */
    .input-premium {
        width: 100%;
        padding: 14px 20px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 12px;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .input-premium { background: #020617; color: #ffffff; border-color: #334155; }
    .input-premium:focus { border-color: #6366f1; outline: none; background: white; }
    .dark .input-premium:focus { background: #0f172a; border-color: #6366f1; }

    /* Buttons */
    .btn-save {
        background: #6366f1;
        color: white !important;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .btn-reset {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e !important;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        border: 1px solid rgba(244, 63, 94, 0.2);
    }
</style>

<div class="company-body px-6 py-10">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-wrap items-center justify-between gap-3 mb-10">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter italic">Company Settings</h1>
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:home-2-bold" class="text-slate-400"></iconify-icon>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Dashboard</span>
                <iconify-icon icon="solar:alt-arrow-right-bold" class="text-slate-300"></iconify-icon>
                <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">Company</span>
            </div>
        </div>

        <?php if($success): ?>
            <div class="mb-6 p-4 bg-emerald-500/10 text-emerald-500 rounded-2xl font-bold text-sm border border-emerald-500/20 flex items-center gap-3">
                <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon> <?= $success ?>
            </div>
        <?php endif; ?>

        <div class="settings-card shadow-sm">
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="col-span-1">
                        <label class="field-label">Full Name *</label>
                        <input type="text" name="company_name" value="<?= e($company['company_name'] ?? '') ?>" class="input-premium" placeholder="Enter Full Name" required>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Email *</label>
                        <input type="email" name="email" value="<?= e($company['email'] ?? '') ?>" class="input-premium" placeholder="Enter email address" required>
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">Phone Number</label>
                        <input type="text" name="phone" value="<?= e($company['phone'] ?? '') ?>" class="input-premium" placeholder="Enter phone number">
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Website</label>
                        <input type="url" name="website" value="<?= e($company['website'] ?? '') ?>" class="input-premium" placeholder="Website URL">
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">Country *</label>
                        <select name="country" class="input-premium appearance-none" required>
                            <option value="India" <?= ($company['country'] ?? '') == 'India' ? 'selected' : '' ?>>India</option>
                            <option value="USA" <?= ($company['country'] ?? '') == 'USA' ? 'selected' : '' ?>>USA</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">City *</label>
                        <select name="city" class="input-premium appearance-none" required>
                            <option value="Delhi" <?= ($company['city'] ?? '') == 'Delhi' ? 'selected' : '' ?>>Delhi</option>
                            <option value="Mumbai" <?= ($company['city'] ?? '') == 'Mumbai' ? 'selected' : '' ?>>Mumbai</option>
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label class="field-label">State *</label>
                        <select name="state" class="input-premium appearance-none" required>
                            <option value="Delhi" <?= ($company['state'] ?? '') == 'Delhi' ? 'selected' : '' ?>>Delhi</option>
                            <option value="Maharashtra" <?= ($company['state'] ?? '') == 'Maharashtra' ? 'selected' : '' ?>>Maharashtra</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="field-label">Zip Code *</label>
                        <input type="text" name="zip_code" value="<?= e($company['zip_code'] ?? '') ?>" class="input-premium" placeholder="Zip Code" required>
                    </div>

                    <div class="col-span-2">
                        <label class="field-label">Address *</label>
                        <textarea name="address" class="input-premium" rows="3" placeholder="Enter Your Address" required><?= e($company['address'] ?? '') ?></textarea>
                    </div>

                    <div class="col-span-2 mt-6 flex justify-center gap-4">
                        <button type="reset" class="btn-reset">Reset</button>
                        <button type="submit" class="btn-save shadow-lg shadow-indigo-500/20">Save Change</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
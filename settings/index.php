<?php
require '../include/load.php';
checkLogin();

// Fetching Settings from ecommerce_db
$title_val  = getSetting('site_title', $pdo);
$email_val  = getSetting('contact_email', $pdo);
$footer_val = getSetting('footer_text', $pdo);
$logo_val   = getSetting('site_logo', $pdo);

$title = 'Global System Settings';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Adaptive Background */
    .dashboard-main-body { 
        background: #f8fafc; 
        min-height: 100vh; 
        display: flex;
        flex-direction: column;
        transition: 0.3s; 
    }
    .dark .dashboard-main-body { background: #020617; }

    /* --- CENTERING LOGIC --- */
    .settings-center-wrapper {
        flex-grow: 1;
        display: flex;
        align-items: center; /* Vertical Center */
        justify-content: center; /* Horizontal Center */
        padding: 60px 20px;
    }

    /* Premium Settings Card */
    .settings-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        padding: 50px;
        width: 100%;
        max-width: 900px;
        transition: 0.3s;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
    }
    .dark .settings-card { 
        background: #0f172a; 
        border-color: #1e293b; 
        box-shadow: none;
    }

    /* Form Labels & Inputs */
    .setting-label {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .dark .setting-label { color: #94a3b8; }

    .setting-input {
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
    .dark .setting-input { background: #1e293b; color: #f1f5f9; }
    .setting-input:focus {
        background: white;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15);
    }
    .dark .setting-input:focus { background: #0f172a; }

    /* Logo Preview Area */
    .logo-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 24px;
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .dark .logo-preview-container { background: #1e293b; border-color: #334155; }

    /* Save Button */
    .btn-save-settings {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff !important;
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
    .btn-save-settings:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5); 
    }

    /* Custom File Upload Button */
    .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        background: #e0e7ff;
        color: #4338ca;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
    }
    .dark .custom-file-upload { background: #312e81; color: #c7d2fe; }
</style>

<div class="dashboard-main-body">
    <div class="settings-center-wrapper">
        <div class="settings-card">
            
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">General Settings</h1>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1">Configure your Project A core identity</p>
                </div>
                <div class="hidden md:block">
                    <iconify-icon icon="solar:settings-bold-duotone" class="text-6xl text-indigo-600/10 dark:text-indigo-400/10"></iconify-icon>
                </div>
            </div>

            <form action="../api/settings/update.php" method="POST" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                    
                    <div class="col-span-1 space-y-8">
                        <div>
                            <label class="setting-label">Website Global Title</label>
                            <input type="text" name="site_title" value="<?= e($title_val) ?>" class="setting-input" placeholder="e.g. Project A Store">
                        </div>

                        <div>
                            <label class="setting-label">Primary Contact Email</label>
                            <input type="email" name="contact_email" value="<?= e($email_val) ?>" class="setting-input" placeholder="admin@project-a.com">
                        </div>

                        <div>
                            <label class="setting-label">Footer Copyright Text</label>
                            <input type="text" name="footer_text" value="<?= e($footer_val) ?>" class="setting-input" placeholder="© 2026 Project A All Rights Reserved">
                        </div>
                    </div>

                    <div class="col-span-1 flex flex-col items-center justify-center border-l border-slate-100 dark:border-slate-800 pl-0 md:pl-10">
                        <label class="setting-label w-full text-center mb-6">Website Identity (Logo)</label>
                        
                        <div class="logo-preview-container">
                            <?php if ($logo_val): ?>
                                <img src="../assets/uploads/<?= e($logo_val) ?>" class="max-w-full max-h-full object-contain p-2">
                            <?php else: ?>
                                <iconify-icon icon="solar:gallery-bold-duotone" class="text-4xl text-slate-300"></iconify-icon>
                            <?php endif; ?>
                        </div>

                        <label for="site_logo" class="custom-file-upload">
                            <iconify-icon icon="solar:upload-bold-duotone" class="inline-block mr-1"></iconify-icon>
                            Update Brand Logo
                        </label>
                        <input type="file" id="site_logo" name="site_logo" class="hidden" onchange="previewImage(this)">
                        
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-4">PNG, JPG or SVG (Max 2MB)</p>
                    </div>

                </div>

                <div class="flex items-center justify-center pt-10 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="btn-save-settings flex items-center gap-2">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="text-xl"></iconify-icon>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.querySelector('.logo-preview-container');
            container.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-full object-contain p-2">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>
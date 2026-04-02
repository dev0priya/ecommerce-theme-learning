<?php 
    require '../include/load.php'; 
    checkLogin(); 

    // Fetching current settings from ecommerce_db
    $paypal_secret = getSetting('paypal_secret', $pdo) ?: '';
    $paypal_public = getSetting('paypal_public', $pdo) ?: '';
    $razor_secret  = getSetting('razor_secret', $pdo) ?: '';
    $razor_public  = getSetting('razor_public', $pdo) ?: '';

    $title = 'Gateway Configuration';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* --- THEME CONTRAST FIXES --- */
    .dashboard-main-body { background: #f8fafc; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; } /* Ultra Dark Bg */

    /* Card Visibility */
    .pg-premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 32px;
        transition: 0.3s;
    }
    .dark .pg-premium-card { 
        background: #0f172a; 
        border-color: #1e293b; 
    }

    /* Labels visibility in Black Theme */
    .pg-label-extreme {
        display: block;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #475569; /* Slate 600 */
        margin-bottom: 10px;
    }
    .dark .pg-label-extreme { 
        color: #f8fafc !important; /* Pure White for Visibility */
    }

    /* Input Styling for Dark Mode */
    .pg-input-contrast {
        width: 100%;
        padding: 14px 20px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 16px;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .pg-input-contrast {
        background: #020617 !important; /* Black background */
        border-color: #334155;
        color: #ffffff !important; /* White text during typing */
    }
    .dark .pg-input-contrast:focus {
        border-color: #6366f1;
        background: #0f172a !important;
    }

    /* Buttons with Glow */
    .btn-pg-action {
        background: #4f46e5;
        color: #ffffff !important;
        padding: 16px 24px;
        border-radius: 16px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    }
    .btn-pg-action:hover { transform: translateY(-2px); background: #6366f1; }

    /* Custom Radio Labels */
    .dark .mode-text { color: #cbd5e1 !important; }
</style>

<div class="dashboard-main-body px-6 py-10">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter italic">Gateway Configuration</h1>
        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[4px] mt-1">Project A Security Protocol Active</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        
        <div class="xl:col-span-6 col-span-12">
            <div class="pg-premium-card overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800">
                            <iconify-icon icon="logos:paypal" class="text-2xl"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white">Paypal</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Express Merchant Account</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                </div>
                
                <div class="p-8">
                    <form action="../api/settings/update_gateways.php" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="col-span-1">
                                <label class="pg-label-extreme">Gateway Mode</label>
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="p_mode" class="w-4 h-4 text-indigo-600" checked>
                                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Sandbox</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="p_mode" class="w-4 h-4 text-indigo-600">
                                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Live Mode</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="pg-label-extreme">Currency</label>
                                <select class="pg-input-contrast appearance-none dark:bg-slate-900">
                                    <option>USD</option>
                                    <option>INR</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="pg-label-extreme">Secret Access Key</label>
                                <input type="password" value="<?= e($paypal_secret) ?>" class="pg-input-contrast" placeholder="Enter Secret Key">
                            </div>
                            <div class="col-span-2">
                                <label class="pg-label-extreme">Client Public ID</label>
                                <input type="text" value="<?= e($paypal_public) ?>" class="pg-input-contrast" placeholder="Enter Client ID">
                            </div>
                            <div class="col-span-2 mt-4">
                                <button type="submit" class="btn-pg-action w-full">
                                    <iconify-icon icon="solar:shield-check-bold" class="text-xl"></iconify-icon>
                                    Save Paypal Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-6 col-span-12">
            <div class="pg-premium-card overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-800">
                            <svg width="28" height="28" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M439.4 445.3H72.6c-13.8 0-25-11.2-25-25V91.7c0-13.8 11.2-25 25-25h366.9c13.8 0 25 11.2 25 25v328.6c-.1 13.8-11.3 25-25.1 25z" fill="#117ACA"/>
                                <path d="M165.4 349.5l140.9-187.1h-84.7l-140.9 187.1h84.7zm181.2 0l140.9-187.1h-84.7l-140.9 187.1h84.7z" fill="white"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white">RazorPay</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">UPI & Card Processing</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                    </label>
                </div>
                
                <div class="p-8">
                    <form action="../api/settings/update_gateways.php" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="col-span-1">
                                <label class="pg-label-extreme">Gateway Mode</label>
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="r_mode" class="w-4 h-4 text-indigo-600" checked>
                                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Test Mode</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="r_mode" class="w-4 h-4 text-indigo-600">
                                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Live Mode</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="pg-label-extreme">Settlement Currency</label>
                                <select class="pg-input-contrast appearance-none dark:bg-slate-900">
                                    <option>INR</option>
                                    <option>USD</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="pg-label-extreme">Razor Secret Key</label>
                                <input type="password" value="<?= e($razor_secret) ?>" class="pg-input-contrast" placeholder="••••••••">
                            </div>
                            <div class="col-span-2">
                                <label class="pg-label-extreme">Razor Public ID (Key)</label>
                                <input type="text" value="<?= e($razor_public) ?>" class="pg-input-contrast" placeholder="rzp_test_...">
                            </div>
                            <div class="col-span-2 mt-4">
                                <button type="submit" class="btn-pg-action w-full">
                                    <iconify-icon icon="solar:shield-keyhole-bold" class="text-xl"></iconify-icon>
                                    Save Razorpay Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
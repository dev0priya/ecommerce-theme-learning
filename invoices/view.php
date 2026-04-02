<?php
require '../include/load.php'; 
checkLogin(); 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Fetch Invoice & Customer Data using PDO
$stmt = $pdo->prepare("SELECT i.*, c.name, c.address, c.email FROM invoices i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.id = ?");
$stmt->execute([$id]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("Invoice not found.");
}

// Fetch Line Items
$items_stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$items_stmt->execute([$id]);
$items = $items_stmt->fetchAll();

$title = 'Invoice Preview';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    .dashboard-main-body { background: #f1f5f9; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Compact Professional Invoice Card */
    .invoice-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .dark .invoice-card { background: #0f172a; border-color: #1e293b; box-shadow: none; }

    /* Small & Sleek Blue Header */
    .invoice-header-compact {
        background: #4f46e5;
        padding: 20px 40px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* --- ADAPTIVE PRINT BUTTON FIX --- */
    .btn-print-adaptive {
        background: #ffffff; 
        color: #1e293b;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }
    .dark .btn-print-adaptive {
        background: #1e293b; 
        color: #ffffff !important;
        border-color: #334151;
    }
    .btn-print-adaptive:hover {
        background: #4f46e5;
        color: #ffffff !important;
        border-color: #4f46e5;
    }

    /* Table Styling */
    .inv-table th {
        font-size: 11px; font-weight: 800; text-transform: uppercase;
        color: #94a3b8; padding: 12px 20px;
        border-bottom: 2px solid #f1f5f9;
    }
    .dark .inv-table th { border-bottom-color: #1e293b; }
    .inv-table td { padding: 15px 20px; color: #334155; border-bottom: 1px solid #f8fafc; font-size: 14px; }
    .dark .inv-table td { color: #cbd5e1; border-bottom-color: #1e293b; }

    /* --- ADAPTIVE STATUS BADGES --- */
    .status-badge-inline {
        padding: 6px 14px; border-radius: 8px; font-weight: 900;
        font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;
        display: inline-block;
    }
    /* Light Theme */
    .st-paid { background: #d1fae5; color: #065f46; }
    .st-unpaid { background: #fee2e2; color: #991b1b; }
    /* Dark Theme Fix */
    .dark .st-paid { background: #064e3b; color: #34d399 !important; border: 1px solid rgba(52, 211, 153, 0.2); }
    .dark .st-unpaid { background: #7f1d1d; color: #fca5a5 !important; border: 1px solid rgba(252, 165, 165, 0.2); }

    @media print {
        .no-print, .sidebar, .header-navbar { display: none !important; }
        .dashboard-main-body { background: white !important; padding: 0 !important; }
        .invoice-card { border: none !important; box-shadow: none !important; max-width: 100% !important; }
        .invoice-header-compact { background: #4f46e5 !important; -webkit-print-color-adjust: exact; }
    }
</style>

<div class="dashboard-main-body px-6 py-8">
    
    <div class="max-w-[850px] mx-auto mb-6 flex justify-between items-center no-print">
        <a href="index.php" class="text-slate-400 dark:text-slate-500 font-bold text-xs uppercase tracking-widest hover:text-indigo-600 flex items-center gap-2">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon> Back to List
        </a>
        <button onclick="window.print()" class="btn-print-adaptive px-5 py-2.5 rounded-xl text-xs font-black flex items-center gap-2 shadow-sm">
            <iconify-icon icon="solar:printer-bold-duotone" class="text-lg"></iconify-icon> 
            PRINT INVOICE
        </button>
    </div>

    <div class="invoice-card">
        <div class="invoice-header-compact">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center font-black text-lg text-white">P</div>
                <h1 class="text-lg font-black tracking-tight uppercase text-white">Project A</h1>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black uppercase opacity-70 mb-0 text-white">Reference</p>
                <p class="text-lg font-black text-white">#<?= $invoice['invoice_number'] ?></p>
            </div>
        </div>

        <div class="p-10 lg:p-14">
            <div class="flex flex-col md:flex-row justify-between items-start mb-12 gap-8">
                <div>
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Billed To</h5>
                    <p class="text-xl font-black text-slate-800 dark:text-white"><?= e($invoice['name'] ?? 'Walk-in Customer') ?></p>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        <?= e($invoice['address'] ?? 'N/A') ?><br>
                        <?= e($invoice['email'] ?? '') ?>
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Invoice Status</h5>
                    <div class="mb-4">
                        <?php $stClass = ($invoice['status'] == 'paid') ? 'st-paid' : 'st-unpaid'; ?>
                        <span class="status-badge-inline <?= $stClass ?>"><?= strtoupper($invoice['status']) ?></span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue Date</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 font-bold"><?= date('D, M d, Y', strtotime($invoice['created_at'])) ?></p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-100 dark:border-slate-800 mb-10">
                <table class="inv-table w-full">
                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                        <tr>
                            <th class="text-left">Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td class="font-bold text-slate-700 dark:text-slate-200"><?= e($item['product_name']) ?></td>
                            <td class="text-center text-slate-400 font-bold"><?= $item['quantity'] ?></td>
                            <td class="text-right text-slate-500 font-medium">₹<?= number_format($item['price_per_unit'], 2) ?></td>
                            <td class="text-right font-black text-slate-900 dark:text-white">₹<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-8 border-t border-slate-100 dark:border-slate-800">
                <div class="w-full max-w-[280px] space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <span class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Subtotal</span>
                        <span class="text-slate-700 dark:text-slate-300 font-bold">₹<?= number_format($invoice['subtotal'], 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center px-2">
                        <span class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Tax Total</span>
                        <span class="text-slate-700 dark:text-slate-300 font-bold">₹<?= number_format($invoice['tax_total'], 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl">
                        <span class="text-indigo-600 font-black uppercase text-xs tracking-widest">Amount Due</span>
                        <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">₹<?= number_format($invoice['grand_total'], 2) ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[4px]">Verified Merchant Receipt</p>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>
<?php 
require '../include/load.php'; 
checkLogin(); 

// Fetch customers for selection
$customers = $pdo->query("SELECT id, name FROM customers ORDER BY name ASC")->fetchAll();

$title = 'Create New Invoice';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* Global Theme Support */
    .dashboard-main-body { background: #f8fafc; min-height: 100vh; transition: 0.3s; }
    .dark .dashboard-main-body { background: #020617; }

    /* Glassmorphism Card */
    .invoice-create-card {
        background: white;
        border-radius: 2rem;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
    }
    .dark .invoice-create-card { background: #0f172a; border-color: #1e293b; }

    /* Form Styling */
    .form-label-pop {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }
    .dark .form-label-pop { color: #94a3b8; }

    .pop-input-styled {
        width: 100%;
        padding: 12px 18px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .pop-input-styled { background: #1e293b; color: #f1f5f9; }
    .pop-input-styled:focus {
        background: white;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .dark .pop-input-styled:focus { background: #0f172a; }

    /* Minimal Table Inputs */
    .table-input {
        background: transparent;
        border: none;
        border-bottom: 2px solid #f1f5f9;
        padding: 8px 5px;
        font-weight: 700;
        width: 100%;
        font-size: 14px;
        color: #1e293b;
        transition: 0.3s;
    }
    .dark .table-input { border-bottom-color: #1e293b; color: #f1f5f9; }
    .table-input:focus { border-bottom-color: #6366f1; outline: none; }

    /* Summary Section */
    .summary-box {
        background: #f8fafc;
        border-radius: 20px;
        padding: 25px;
    }
    .dark .summary-box { background: #1e293b; }

    /* Action Buttons */
    .btn-add-row {
        background: #f1f5f9;
        color: #6366f1;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 11px;
        transition: 0.3s;
    }
    .dark .btn-add-row { background: #0f172a; color: #818cf8; }
    .btn-add-row:hover { background: #6366f1; color: white; transform: translateY(-2px); }

    .btn-post-invoice {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        padding: 15px 40px;
        border-radius: 16px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }
    .btn-post-invoice:hover { transform: translateY(-3px); box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5); }
</style>

<div class="dashboard-main-body px-6 py-10 lg:px-12">
    <div class="max-w-6xl mx-auto">
        <div class="invoice-create-card shadow-xl border-t-8 border-indigo-600">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Draft Invoice</h1>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1">Fill in the details to generate bill</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-slate-400 uppercase">Status:</span>
                    <span class="px-4 py-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-full border border-amber-100 dark:border-amber-900/30 uppercase tracking-widest">DRAFT MODE</span>
                </div>
            </div>

            <form action="save_invoice.php" method="POST">
                <div class="grid grid-cols-12 gap-8 mb-12">
                    <div class="col-span-12 md:col-span-6 lg:col-span-8">
                        <label class="form-label-pop">Choose Recipient</label>
                        <div class="relative">
                            <select name="customer_id" class="pop-input-styled appearance-none" required>
                                <option value="" disabled selected>Search or select a customer...</option>
                                <?php foreach($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <iconify-icon icon="solar:users-group-rounded-bold" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 lg:col-span-4">
                        <label class="form-label-pop">Invoice Date</label>
                        <input type="date" name="invoice_date" value="<?= date('Y-m-d') ?>" class="pop-input-styled">
                    </div>
                </div>

                <div class="mb-10 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                                <th class="pb-5 px-2">Item Description</th>
                                <th class="pb-5 px-2 w-24 text-center">Qty</th>
                                <th class="pb-5 px-2 w-40">Unit Price (₹)</th>
                                <th class="pb-5 px-2 text-right w-40">Line Total</th>
                                <th class="pb-5 px-2 w-16"></th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">
                            <tr class="border-b border-slate-50 dark:border-slate-900/50 group">
                                <td class="py-5 px-2">
                                    <input type="text" name="item_name[]" class="table-input" placeholder="e.g. Graphic Design Services" required>
                                </td>
                                <td class="py-5 px-2">
                                    <input type="number" name="item_qty[]" class="table-input text-center qty" value="1" min="1">
                                </td>
                                <td class="py-5 px-2">
                                    <input type="number" name="item_price[]" class="table-input price" placeholder="0.00" step="0.01">
                                </td>
                                <td class="py-5 px-2 font-black text-right text-slate-900 dark:text-white item-total">₹0.00</td>
                                <td class="py-5 px-2 text-right">
                                    <button type="button" class="text-slate-300 hover:text-rose-500 transition-colors" onclick="removeRow(this)">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" class="text-xl"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-12 gap-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <div class="col-span-12 lg:col-span-6">
                        <button type="button" onclick="addRow()" class="btn-add-row flex items-center gap-2">
                            <iconify-icon icon="solar:add-circle-bold" class="text-lg"></iconify-icon>
                            Add New Line
                        </button>
                        <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Internal Note</p>
                            <textarea name="notes" class="bg-transparent w-full text-sm font-medium outline-none text-slate-600 dark:text-slate-400" placeholder="Add terms or notes here..." rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-6">
                        <div class="summary-box space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-black text-slate-400 uppercase">Subtotal</span>
                                <span class="font-black text-slate-800 dark:text-white" id="subtotal">₹0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-black text-slate-400 uppercase">Tax (GST 18%)</span>
                                <span class="font-black text-slate-800 dark:text-white" id="tax">₹0.00</span>
                            </div>
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                                <span class="text-sm font-black text-indigo-600 uppercase tracking-widest">Total Amount</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white" id="grandTotal">₹0.00</span>
                            </div>
                        </div>
                        <div class="mt-10 text-right">
                            <button type="submit" name="submit_invoice" class="btn-post-invoice flex items-center gap-2 float-right">
                                <iconify-icon icon="solar:check-read-bold" class="text-xl"></iconify-icon>
                                Finalize & Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addRow() {
    const body = document.getElementById('itemBody');
    const newRow = `
    <tr class="border-b border-slate-50 dark:border-slate-900/50 group">
        <td class="py-5 px-2"><input type="text" name="item_name[]" class="table-input" placeholder="Product name..." required></td>
        <td class="py-5 px-2"><input type="number" name="item_qty[]" class="table-input text-center qty" value="1" min="1"></td>
        <td class="py-5 px-2"><input type="number" name="item_price[]" class="table-input price" placeholder="0.00" step="0.01"></td>
        <td class="py-5 px-2 font-black text-right text-slate-900 dark:text-white item-total">₹0.00</td>
        <td class="py-5 px-2 text-right">
            <button type="button" class="text-slate-300 hover:text-rose-500 transition-colors" onclick="removeRow(this)">
                <iconify-icon icon="solar:trash-bin-trash-bold" class="text-xl"></iconify-icon>
            </button>
        </td>
    </tr>`;
    body.insertAdjacentHTML('beforeend', newRow);
}

function removeRow(btn) {
    const rows = document.querySelectorAll('#itemBody tr');
    if(rows.length > 1) {
        btn.closest('tr').remove();
        calculateTotals();
    }
}

function calculateTotals() {
    let subtotal = 0;
    const rows = document.querySelectorAll('#itemBody tr');
    
    rows.forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').innerText = '₹' + total.toFixed(2);
        subtotal += total;
    });

    const tax = subtotal * 0.18; // 18% GST example
    const grandTotal = subtotal + tax;

    document.getElementById('subtotal').innerText = '₹' + subtotal.toFixed(2);
    document.getElementById('tax').innerText = '₹' + tax.toFixed(2);
    document.getElementById('grandTotal').innerText = '₹' + grandTotal.toFixed(2);
}

document.addEventListener('input', function(e) {
    if(e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        calculateTotals();
    }
});
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>
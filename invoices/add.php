<?php 
require '../include/load.php'; 
checkLogin(); 

$customers = $pdo->query("SELECT id, name FROM customers")->fetchAll();
$title = 'Draft New Invoice';
include '../partials/layouts/layoutTop.php'; 
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --slate-900: #0f172a;
        --bg-soft: #f8fafc;
    }

    .dashboard-main-body { background: var(--bg-soft); min-height: 100vh; }

    /* Compact Card */
    .vibrant-card-pop {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    /* Tight Inputs */
    .pop-input-styled {
        width: 100%;
        padding: 10px 15px;
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 12px;
        font-weight: 700;
        color: var(--slate-900);
        outline: none;
        transition: 0.2s;
    }
    .pop-input-styled:focus {
        background: white;
        border-color: var(--pop-indigo);
    }

    /* Minimal Table Inputs */
    .table-input {
        background: transparent;
        border: none;
        border-bottom: 2px solid #f1f5f9;
        padding: 5px;
        font-weight: 700;
        width: 100%;
    }
    .table-input:focus {
        border-bottom-color: var(--pop-indigo);
        outline: none;
    }

    /* Compact Button */
    .btn-submit-pop {
        background: linear-gradient(135deg, var(--pop-indigo) 0%, #4f46e5 100%);
        color: white;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-submit-pop:hover { transform: translateY(-2px); }
</style>

<div class="dashboard-main-body px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="vibrant-card-pop p-8 border-t-8 border-indigo-600">
            
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Draft New Invoice</h1>
                <p class="text-indigo-600 font-bold text-xs uppercase">Auto-Generated Ref.</p>
            </div>

            <form method="POST">
                <div class="mb-6 p-5 bg-indigo-50/40 rounded-2xl border border-dashed border-indigo-200">
                    <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Customer Selection</label>
                    <select name="customer_id" class="pop-input-styled text-sm">
                        <option value="" disabled selected>Choose a customer...</option>
                        <?php foreach($customers as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                                <th class="pb-3">Product Description</th>
                                <th class="pb-3 w-20 text-center">Qty</th>
                                <th class="pb-3 w-32">Price (₹)</th>
                                <th class="pb-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="itemBody">
                            <tr class="border-b border-slate-50">
                                <td class="py-3 pr-2">
                                    <input type="text" name="item_name[]" class="table-input" placeholder="Product name..." required>
                                </td>
                                <td class="py-3 px-1">
                                    <input type="number" name="item_qty[]" class="table-input text-center qty" value="1">
                                </td>
                                <td class="py-3 px-1">
                                    <input type="number" name="item_price[]" class="table-input price" placeholder="0.00">
                                </td>
                                <td class="py-3 font-bold text-right text-slate-700 item-total">₹0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                    <button type="button" onclick="addRow()" class="text-indigo-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-1 hover:text-slate-900 transition-all">
                        <iconify-icon icon="solar:add-circle-bold" class="text-lg"></iconify-icon>
                        Add Row
                    </button>
                    
                    <button type="submit" name="submit_invoice" class="btn-submit-pop">
                        Post Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addRow() {
    const body = document.getElementById('itemBody');
    const newRow = `
    <tr class="border-b border-slate-50">
        <td class="py-3 pr-2"><input type="text" name="item_name[]" class="table-input" placeholder="Product name..." required></td>
        <td class="py-3 px-1"><input type="number" name="item_qty[]" class="table-input text-center qty" value="1"></td>
        <td class="py-3 px-1"><input type="number" name="item_price[]" class="table-input price" placeholder="0.00"></td>
        <td class="py-3 font-bold text-right text-slate-700 item-total">₹0.00</td>
    </tr>`;
    body.insertAdjacentHTML('beforeend', newRow);
}

document.addEventListener('input', function(e) {
    if(e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        const row = e.target.closest('tr');
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').innerText = '₹' + total.toFixed(2);
    }
});
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>
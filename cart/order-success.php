<?php
require '../include/load.php';
checkLogin();

$invoice_id = $_GET['id'] ?? null;

if (!$invoice_id) {
    header("Location: ../index.php");
    exit();
}

// Fetch invoice details for the success screen
$stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ? AND customer_id = ?");
$stmt->execute([$invoice_id, $_SESSION['user_id']]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("Unauthorized access or invoice not found.");
}

$title = 'Order Successful';
include '../partials/head.php';
?>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-emerald: #10b981;
        --slate-900: #0f172a;
        --bg-soft: #f8fafc;
    }

    body { 
        background: var(--bg-soft); 
        font-family: 'Inter', sans-serif; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        min-height: 100vh; 
        margin: 0; 
    }

    .success-card {
        background: white;
        padding: 50px 40px;
        border-radius: 3rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        max-width: 500px;
        width: 100%;
        text-align: center;
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    /* Success Icon Pop */
    .icon-container {
        width: 100px;
        height: 100px;
        background: #d1fae5;
        color: var(--pop-emerald);
        border-radius: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 4rem;
        animation: pop-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes pop-in {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    h1 { font-weight: 900; color: var(--slate-900); font-size: 2.2rem; margin-bottom: 10px; letter-spacing: -0.03em; }
    p { color: #64748b; font-weight: 500; line-height: 1.6; margin-bottom: 35px; }

    .invoice-info {
        background: #f8fafc;
        padding: 20px;
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 35px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-row { display: flex; justify-content: space-between; font-size: 0.9rem; }
    .label { color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .value { color: var(--slate-900); font-weight: 800; }

    /* Buttons Pop */
    .btn-group { display: flex; flex-direction: column; gap: 15px; }

    .btn-main {
        background: var(--pop-indigo);
        color: white;
        padding: 16px;
        border-radius: 1.25rem;
        font-weight: 800;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    .btn-main:hover { 
        transform: translateY(-3px); 
        background: var(--slate-900); 
        box-shadow: 0 15px 20px -5px rgba(15, 23, 42, 0.2);
    }

    .btn-outline {
        color: var(--pop-indigo);
        font-weight: 700;
        text-decoration: none;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .btn-outline:hover { color: var(--slate-900); text-decoration: underline; }

</style>

<div class="success-card">
    <div class="icon-container">
        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
    </div>

    <h1>Payment Success!</h1>
    <p>Your transaction was authorized successfully. We are now preparing your masterpieces for shipment.</p>

    <div class="invoice-info">
        <div class="info-row">
            <span class="label">Invoice Number</span>
            <span class="value"><?= e($invoice['invoice_number']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Amount Paid</span>
            <span class="value" style="color: var(--pop-emerald);">₹<?= number_format($invoice['grand_total'], 2) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Status</span>
            <span class="value" style="text-transform: uppercase; color: var(--pop-emerald);">Paid</span>
        </div>
    </div>

    <div class="btn-group">
        <a href="../index.php" class="btn-main">CONTINUE SHOPPING</a>
        <a href="../admin/view.php?id=<?= $invoice_id ?>" class="btn-outline">View Digital Invoice</a>
    </div>
</div>

</body>
</html>
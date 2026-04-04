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

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
    :root {
        --bg-main: #020617;
        --bg-card: rgba(30, 41, 59, 0.4);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --neon-emerald: #10b981; /* Default Emerald */
        --neon-mint: #00f59b;    /* ✅ Bright Mint Green for Heading */
        --neon-purple: #a855f7;
        --border: rgba(255, 255, 255, 0.08);
        --glass-bg: blur(16px);
    }

    /* Light Theme Support */
    .light body, body.light {
        --bg-main: #f8fafc;
        --bg-card: rgba(255, 255, 255, 0.8);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --neon-mint: #00cc80;    /* ✅ Slightly Darker Mint for Light Background */
        --border: rgba(0, 0, 0, 0.05);
    }

    body { 
        background: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        min-height: 100vh; 
        margin: 0; 
        color: var(--text-main);
    }

    /* PREMIUM SUCCESS CARD */
    .success-card {
        background: var(--bg-card);
        backdrop-filter: var(--glass-bg);
        padding: 60px 40px;
        border-radius: 40px;
        border: 1px solid var(--border);
        max-width: 480px;
        width: 90%;
        text-align: center;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
        position: relative;
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* CELEBRATION ICON */
    .icon-wrapper {
        width: 120px;
        height: 120px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--neon-emerald);
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 5rem;
        position: relative;
        animation: pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes pop {
        0% { transform: scale(0); }
        80% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* GLOW EFFECT */
    .icon-wrapper::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: var(--neon-emerald);
        filter: blur(40px);
        opacity: 0.2;
        z-index: -1;
    }

    /* ✅ Heading in Mint Green with slight glow */
    h1 { 
        font-weight: 800; 
        font-size: 2.2rem; 
        margin-bottom: 12px; 
        letter-spacing: -1px; 
        color: var(--neon-mint); 
        text-shadow: 0 0 20px rgba(0, 245, 155, 0.3);
    }

    p { color: var(--text-muted); font-weight: 500; line-height: 1.6; margin-bottom: 40px; font-size: 0.95rem; }

    /* INVOICE DETAILS BOX */
    .invoice-premium-box {
        background: rgba(0, 0, 0, 0.2);
        padding: 25px;
        border-radius: 24px;
        border: 1px solid var(--border);
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .light .invoice-premium-box { background: rgba(255, 255, 255, 0.5); }

    .info-row { display: flex; justify-content: space-between; font-size: 0.85rem; }
    .label { color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 0.7rem; }
    .value { color: var(--text-main); font-weight: 700; }

    /* ACTION BUTTONS */
    .btn-group { display: flex; flex-direction: column; gap: 15px; }

    .btn-neon {
        background: var(--neon-purple);
        color: white;
        padding: 18px;
        border-radius: 18px;
        font-weight: 800;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        transition: 0.3s;
        box-shadow: 0 10px 25px rgba(168, 85, 247, 0.3);
    }

    .btn-neon:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 15px 35px rgba(168, 85, 247, 0.5);
    }

    .btn-link {
        color: var(--text-muted);
        font-weight: 700;
        text-decoration: none;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.2s;
    }
    .btn-link:hover { color: var(--text-main); }

</style>

<div class="success-card">
    <div class="icon-wrapper">
        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
    </div>

    <h1>Success! 🎉</h1>
    <p>Your payment was confirmed. We've started preparing your order for delivery.</p>

    <div class="invoice-premium-box">
        <div class="info-row">
            <span class="label">Invoice No.</span>
            <span class="value">#<?= e($invoice['invoice_number']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Amount Paid</span>
            <span class="value" style="color: var(--neon-emerald);">₹<?= number_format($invoice['grand_total'], 2) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Status</span>
            <span class="value">
                <span style="background: rgba(16, 185, 129, 0.1); color: var(--neon-emerald); padding: 4px 10px; border-radius: 8px; font-size: 0.7rem;">COMPLETED</span>
            </span>
        </div>
    </div>

    <div class="btn-group">
        <a href="../index.php" class="btn-neon">Continue Shopping →</a>
        
    </div>

    <div style="margin-top: 30px; border-top: 1px solid var(--border); pt-20">
        <p style="font-size: 11px; margin-top: 20px; opacity: 0.5;">
            A confirmation email has been sent to your registered address.
        </p>
    </div>
</div>

</body>
</html>
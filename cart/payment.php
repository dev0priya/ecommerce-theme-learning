<?php
session_start();
require '../include/load.php';
checkLogin();

// Cart empty check
if (empty($_SESSION['cart'])) {
    header('Location: ../index.php');
    exit;
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $price = $stmt->fetchColumn();
    $total += $price * $qty;
}

// STRIPE setup
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$intent = \Stripe\PaymentIntent::create([
    'amount' => $total * 100,
    'currency' => 'inr',
    'metadata' => ['user_id' => $_SESSION['user_id']]
]);

include '../partials/header.php';
?>

<script src="https://js.stripe.com/v3/"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #020617;
    --bg-card: rgba(30, 41, 59, 0.5);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-blue: #00f2ff;
    --neon-purple: #a855f7;
    --border: rgba(255, 255, 255, 0.08);
    --glass-bg: blur(16px);
}

/* Light Theme Variables */
.light body, body.light {
    --bg-main: #f8fafc;
    --bg-card: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --neon-blue: #2563eb;
    --neon-purple: #7c3aed;
    --border: rgba(0, 0, 0, 0.05);
}

body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-main); color: var(--text-main); }

/* SEAMLESS LAYOUT STRUCTURE */
.user-panel-wrapper { display: flex; min-height: 100vh; position: relative; }
.main-content-area { flex: 1; margin-left: 280px; display: flex; flex-direction: column; min-width: 0; }
.content-body { padding: 40px; flex-grow: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; }

/* PAYMENT CARD BOX */
.payment-card-premium {
    width: 100%;
    max-width: 480px;
    background: var(--bg-card);
    backdrop-filter: var(--glass-bg);
    border: 1px solid var(--border);
    border-radius: 32px;
    padding: 40px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.section-label { font-size: 10px; font-weight: 900; text-transform: uppercase; color: var(--text-muted); letter-spacing: 4px; display: block; margin-bottom: 8px; text-align: center; }
.payment-title { font-size: 28px; font-weight: 900; text-align: center; margin-bottom: 30px; color: var(--text-main); }

/* AMOUNT DISPLAY */
.amount-badge {
    background: rgba(0, 242, 255, 0.1);
    border: 1px solid rgba(0, 242, 255, 0.2);
    padding: 15px;
    border-radius: 20px;
    text-align: center;
    margin-bottom: 30px;
}
.amount-badge span { display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; }
.amount-badge h2 { font-size: 24px; font-weight: 900; color: var(--neon-blue); margin: 0; }

/* FORM ELEMENTS */
.form-group { margin-bottom: 20px; }
.form-label { font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px; display: block; }

.input-premium {
    width: 100%;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--border);
    padding: 14px 18px;
    border-radius: 14px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    transition: 0.3s;
}
.light .input-premium, body.light .input-premium { background: #fff; color: #000; border-color: #cbd5e1; }
.input-premium:focus { border-color: var(--neon-blue); outline: none; box-shadow: 0 0 15px rgba(0, 242, 255, 0.1); }

/* STRIPE ELEMENT STYLE */
#card-element {
    background: rgba(0, 0, 0, 0.2);
    padding: 16px;
    border-radius: 14px;
    border: 1px solid var(--border);
}
.light #card-element, body.light #card-element { background: #fff; border-color: #cbd5e1; }

/* NEON PAY BUTTON */
.pay-btn {
    width: 100%;
    margin-top: 30px;
    padding: 18px;
    background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
    color: #fff;
    border: none;
    border-radius: 16px;
    font-weight: 800;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 10px 25px rgba(0, 242, 255, 0.2);
}
.pay-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0, 242, 255, 0.4); }
.pay-btn:disabled { opacity: 0.5; cursor: not-allowed; }

#error-message { color: #ff4444; font-size: 12px; font-weight: 600; margin-top: 15px; text-align: center; }

/* FOOTER FIX */
.footer-wrapper { width: 100%; margin-top: auto; border-top: 1px solid var(--border); }

@media (max-width: 991px) { .main-content-area { margin-left: 0 !important; } }
</style>

<div class="user-panel-wrapper">
    <?php include '../partials/sidebar-user.php'; ?>

    <div class="main-content-area">
        <div class="content-body">
            
            <div class="payment-card-premium">
                <span class="section-label">Secure Gateway</span>
                <h1 class="payment-title">Payment 💳</h1>

                <div class="amount-badge">
                    <span>Total Payable Amount</span>
                    <h2>₹<?= number_format($total, 2) ?></h2>
                </div>

                <form id="payment-form">
                    <div class="form-group">
                        <label class="form-label">Cardholder Name</label>
                        <input type="text" id="cardholder-name" class="input-premium" placeholder="e.g. Rahul Sharma" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Card Details</label>
                        <div id="card-element">
                            </div>
                    </div>

                    <button id="submit" class="pay-btn">
                        Complete Payment
                    </button>

                    <div id="error-message"></div>
                </form>

                <div style="margin-top: 25px; text-align: center; opacity: 0.6; font-size: 10px; letter-spacing: 1px;">
                    🔒 PCI-DSS COMPLIANT • 256-BIT ENCRYPTION
                </div>
            </div>

        </div>

        <div class="footer-wrapper">
            <?php include '../partials/footer.php'; ?>
        </div>
    </div>
</div>

<script>
const stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
const elements = stripe.elements();

// Dynamic theme detection for Stripe Element
const isDarkMode = document.documentElement.classList.contains('dark') || 
                   document.body.classList.contains('dark') ||
                   window.getComputedStyle(document.body).backgroundColor !== 'rgb(248, 250, 252)';

const style = {
    base: {
        color: isDarkMode ? '#ffffff' : '#0f172a', // White for Dark, Black for Light
        fontFamily: '"Plus Jakarta Sans", sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': { 
            color: isDarkMode ? '#94a3b8' : '#64748b' // Balanced placeholder visibility
        }
    },
    invalid: { color: '#ff4444', iconColor: '#ff4444' }
};

const card = elements.create('card', { style: style, hidePostalCode: true });
card.mount('#card-element');

const form = document.getElementById('payment-form');
const payBtn = document.getElementById('submit');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    payBtn.disabled = true;
    payBtn.innerText = 'Processing...';

    const {paymentIntent, error} = await stripe.confirmCardPayment(
        '<?= $intent->client_secret ?>',
        {
            payment_method: {
                card: card,
                billing_details: {
                    name: document.getElementById('cardholder-name').value
                }
            }
        }
    );

    if (error) {
        document.getElementById('error-message').innerText = error.message;
        payBtn.disabled = false;
        payBtn.innerText = 'Complete Payment';
    } else {
        window.location.href = 'success.php?tid=' + paymentIntent.id;
    }
});
</script>

</body>
</html>
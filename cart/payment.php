<?php
require '../include/load.php'; // Fixed path
checkLogin();

if (empty($_SESSION['cart'])) {
    header('Location: ../index.php'); // Fixed path
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $price = $stmt->fetchColumn();
    $total += $price * $qty;
}

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$intent = \Stripe\PaymentIntent::create([
    'amount' => $total * 100, 
    'currency' => 'usd',
    'metadata' => ['user_id' => $_SESSION['user_id']]
]);

// FIXED: Added ../ to reach partials folder
include '../partials/header.php'; 
?>

<script src="https://js.stripe.com/v3/"></script>

<style>
    :root {
        --pop-indigo: #6366f1;
        --pop-purple: #a855f7;
        --pop-emerald: #10b981;
        --slate-900: #0f172a;
        --bg-soft: #fbfdff;
    }
    body { background: var(--bg-soft); font-family: 'Inter', sans-serif; }
    .payment-container { padding: 40px 20px; display: flex; align-items: center; justify-content: center; min-height: 80vh; }
    
    .vibrant-card-pop {
        background: white;
        border-radius: 2.5rem;
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.1);
        border: 1px solid #f1f5f9;
        max-width: 480px;
        width: 100%;
        overflow: hidden;
    }
    
    .btn-submit-pop {
        background: linear-gradient(135deg, var(--pop-indigo) 0%, var(--pop-purple) 100%);
        color: white;
        padding: 16px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        transition: 0.3s;
        box-shadow: 0 12px 20px rgba(99, 102, 241, 0.3);
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .btn-submit-pop:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(99, 102, 241, 0.4); }

    #card-element {
        background: #f8fafc;
        padding: 16px;
        border-radius: 15px;
        border: 2px solid #eef2ff;
        margin-bottom: 25px;
    }
</style>

<div class="payment-container antialiased">
    <div class="vibrant-card-pop p-10 border-t-[10px] border-indigo-600">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
            <h1 class="text-2xl font-black text-slate-900 tracking-tighter">Secure Checkout</h1>
            <p class="font-black text-xl text-emerald-600">₹<?= number_format($total, 2) ?></p>
        </div>

        <form id="payment-form">
            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cardholder Name</label>
                <input type="text" id="cardholder-name" class="w-full p-4 bg-slate-50 border-none rounded-xl font-bold" placeholder="Full Name" required>
            </div>

            <div id="card-element"></div>
            
            <button id="submit" class="btn-submit-pop">
                Authorize Payment
            </button>
            <div id="error-message" class="text-center mt-4 text-rose-500 font-bold text-xs"></div>
        </form>
    </div>
</div>

<script>
    const stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const {paymentIntent, error} = await stripe.confirmCardPayment('<?= $intent->client_secret ?>', {
            payment_method: { card: card, billing_details: { name: document.getElementById('cardholder-name').value } }
        });

        if (error) {
            document.getElementById('error-message').textContent = error.message;
        } else {
            window.location.href = 'success.php?tid=' + paymentIntent.id;
        }
    });
</script>

<?php include '../partials/footer.php'; // Fixed path ?>
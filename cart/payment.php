<?php
session_start();
require '../include/load.php';
checkLogin();

// cart empty check
if (empty($_SESSION['cart'])) {
    header('Location: ../index.php');
    exit;
}

// calculate total
$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $price = $stmt->fetchColumn();
    $total += $price * $qty;
}

// STRIPE
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

$intent = \Stripe\PaymentIntent::create([
    'amount' => $total * 100,
    'currency' => 'inr', // 🔥 FIX (India use INR)
    'metadata' => ['user_id' => $_SESSION['user_id']]
]);

include '../partials/header.php';
?>

<script src="https://js.stripe.com/v3/"></script>

<style>
body {
    background: #f8fafc;
    font-family: 'Inter', sans-serif;
}
.payment-container {
    padding: 40px;
    display: flex;
    justify-content: center;
}
.card-box {
    background: #fff;
    padding: 30px;
    border-radius: 20px;
    width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}
.pay-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}
</style>

<div class="payment-container">
    <div class="card-box">
        <h2>Payment 💳</h2>
        <p>Total: ₹<?= number_format($total,2) ?></p>

        <form id="payment-form">
            <input type="text" id="cardholder-name" placeholder="Full Name" required style="width:100%;padding:10px;margin:10px 0;">
            
            <div id="card-element"></div>

            <button id="submit" class="pay-btn">
                Pay Now
            </button>

            <div id="error-message"></div>
        </form>
    </div>
</div>

<script>
const stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();

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
    } else {
        // ✅ NEXT STEP
        window.location.href = 'success.php?tid=' + paymentIntent.id;
    }
});
</script>

<?php include '../partials/footer.php'; ?>
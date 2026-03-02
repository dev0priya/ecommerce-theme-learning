<?php
require '../include/load.php';
checkLogin();

// Ensure cart exists
if (empty($_SESSION['cart'])) {
    redirect('../cart/index.php');
}

// 1. Calculate total securely from DB
$total = 0;

foreach ($_SESSION['cart'] as $pid => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $price = $stmt->fetchColumn();

    $total += $price * $qty;
}

// 2. Setup Stripe (SECRET KEY)
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// 3. Create Payment Intent
$intent = \Stripe\PaymentIntent::create([
    'amount'   => $total * 100, // Stripe works in cents
    'currency' => 'usd',
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .StripeElement {
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Pay $<?= number_format($total, 2) ?></h1>

<form id="payment-form">
    <div id="card-element"></div>
    <button id="submit">Pay Now</button>
    <div id="error-message" style="color:red;"></div>
</form>

<script>
    // 🔴 PUT YOUR PUBLIC KEY
    const stripe = Stripe('<?= $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const { paymentIntent, error } = await stripe.confirmCardPayment(
            '<?= $intent->client_secret ?>',
            { payment_method: { card: card } }
        );

        if (error) {
            document.getElementById('error-message').textContent = error.message;
        } else {
            // SUCCESS → Redirect
            window.location.href = 'success.php?tid=' + paymentIntent.id;
        }
    });
</script>

</body>
</html>

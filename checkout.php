<?php
session_start();

// calculate total
$grand_total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty. <a href="products.php">Go to Shop</a></p>
        <?php exit; ?>
    <?php endif; ?>

    <p>Total Amount: <strong>₹<?php echo number_format($grand_total,2); ?></strong></p>

    <form action="place_order.php" method="POST">
        <label>Name:</label><br>
        <input type="text" name="customer_name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="customer_email" required><br><br>

        <label>Address:</label><br>
        <textarea name="customer_address" required></textarea><br><br>

        <label>Payment Mode:</label><br>
        <select name="payment_mode" required>
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="Online Payment">Online Payment (Simulated)</option>
            <option value="UPI">UPI</option>
            <option value="Card Payment">Card Payment</option>
        </select><br><br>

        <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>">

        <button type="submit">Place Order</button>
    </form>

    <br>
    <a href="cart.php">← Back to Cart</a>
</body>
</html>

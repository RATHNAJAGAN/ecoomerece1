<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ecommerce1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

// Required fields
$customer_name    = trim($_POST['customer_name']    ?? '');
$customer_email   = trim($_POST['customer_email']   ?? '');
$customer_address = trim($_POST['customer_address'] ?? '');
$payment_mode     = trim($_POST['payment_mode']     ?? '');
$total_amount     = floatval($_POST['total_amount'] ?? 0.0);

// basic validation
if ($customer_name === '' || $customer_email === '' || $customer_address === '' || $payment_mode === '') {
    die("All fields are required. <a href='checkout.php'>Back to checkout</a>");
}

if (empty($_SESSION['cart'])) {
    die("Your cart is empty. <a href='products.php'>Shop now</a>");
}

// --- Simulate payment for "Online Payment" ---
$payment_status = 'Pending';
$payment_reference = null;

if ($payment_mode === 'Online Payment') {
    // simulate a successful payment (no gateway)
    // create a fake reference id string
    $payment_status = 'Paid';
    $payment_reference = 'SIMPAY-' . strtoupper(substr(md5(uniqid('', true)),0,10));
}

// calculate grand total from session to be safe
$grand_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $grand_total += $item['price'] * $item['quantity'];
}

// Insert order into orders table
// Make sure your orders table has the columns used below
$stmt = $conn->prepare(
    "INSERT INTO orders (customer_name, customer_email, customer_address, payment_mode, total, created_at) VALUES (?, ?, ?, ?, ?, NOW())"
);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ssssd", $customer_name, $customer_email, $customer_address, $payment_mode, $grand_total);
if (!$stmt->execute()) {
    die("Insert order failed: " . $stmt->error);
}
$order_id = $stmt->insert_id;
$stmt->close();

// Insert items into order_items
// Table order_items should have columns: order_id, product_name, price, quantity, image (image optional)
$stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");

if (!$stmt_item) {
    die("Prepare failed (order_items): " . $conn->error);
}

foreach ($_SESSION['cart'] as $item) {
    // in earlier code item keys might be 'name' or 'product_name' — adjust if needed
    $product_name = $item['name'] ?? $item['product_name'] ?? 'Unnamed';
    $price = floatval($item['price']);
    $qty = intval($item['quantity']);
    $image = $item['image'] ?? null;
   $stmt_item->bind_param("isid", $order_id, $product_name, $price, $qty);

    if (!$stmt_item->execute()) {
        // don't die — but capture errors
        error_log("Insert order_item failed: " . $stmt_item->error);
    }
}
$stmt_item->close();

// Optionally store payment reference into a payments table or orders (if you have a column)
// If your orders table has a payment_reference or payment_status columns, you could update them. Example:
// $conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_status VARCHAR(30) DEFAULT 'Pending'");
// $conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_reference VARCHAR(100) DEFAULT NULL");
// For simplicity we'll skip altering DB here.

// Save last order id to session and clear cart
$_SESSION['last_order_id'] = $order_id;
$_SESSION['last_order_payment_status'] = $payment_status;
$_SESSION['last_order_payment_reference'] = $payment_reference;
$_SESSION['cart'] = [];

// Redirect to show order summary (order_success.php)
header("Location: order_success.php");
exit;

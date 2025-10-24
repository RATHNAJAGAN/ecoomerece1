<?php
session_start();
require('razorpay-php/Razorpay.php'); // Download SDK from Razorpay GitHub

use Razorpay\Api\Api;

// Your Razorpay API Keys
$keyId = "rzp_test_xxxxxxxx";   // Replace with your Key ID
$keySecret = "your_key_secret"; // Replace with your Key Secret

$api = new Api($keyId, $keySecret);

// Calculate total
$grand_total = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $grand_total += $item['price'] * $item['quantity'];
    }
} else {
    $grand_total = 500; // fallback
}

$orderData = [
    'receipt'         => 'order_rcptid_11',
    'amount'          => $grand_total * 100, // paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$order = $api->order->create($orderData);

// Send back order details to checkout
echo json_encode([
    "order_id" => $order['id'],
    "amount"   => $grand_total * 100,
    "currency" => "INR",
    "key"      => $keyId
]);

<?php
session_start();
if (!isset($_SESSION['last_order_id'])) {
    echo "<p>No recent order found. <a href='index.php'>Go Home</a></p>";
    exit;
}

$order_id = intval($_SESSION['last_order_id']);

$conn = new mysqli("localhost", "root", "", "ecommerce1");
if ($conn->connect_error) die("DB error: " . $conn->connect_error);

// fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<p>Order not found. <a href='index.php'>Go Home</a></p>";
    exit;
}

// fetch items
$stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result();
$stmt2->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
    <style>
        body{font-family: Arial; margin: 20px;}
        table{border-collapse: collapse; width: 100%;}
        th, td{border:1px solid #ddd; padding:8px; text-align: center;}
        th{background:#f4f4f4;}
    </style>
</head>
<body>
    <h1>✅ Order Placed</h1>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
    <p><strong>Payment Mode:</strong> <?php echo htmlspecialchars($order['payment_mode']); ?></p>
    <p><strong>Total:</strong> ₹<?php echo number_format($order['total'],2); ?></p>

    <h2>Ordered Items</h2>
    <table>
        <tr><th>Image</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
        <?php while ($row = $items->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" width="80" alt="">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td>₹<?php echo number_format($row['price'],2); ?></td>
                <td><?php echo intval($row['quantity']); ?></td>
                <td>₹<?php echo number_format($row['price'] * $row['quantity'],2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="index.php">← Back to Home</a>
</body>
</html>

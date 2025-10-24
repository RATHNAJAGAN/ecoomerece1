<?php
$conn = new mysqli("localhost", "root", "", "ecommerce1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all orders
$sql = "SELECT * FROM orders ORDER BY id DESC";
$orders = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        header { background: #333; color: #fff; padding: 15px; text-align: center; }
        nav { margin: 20px; text-align: center; }
        nav a { text-decoration: none; color: #333; padding: 10px 20px; margin: 5px; background: #ddd; border-radius: 5px; }
        nav a:hover { background: #999; color: #fff; }
        .orders { margin: 20px auto; width: 80%; background: #fff; padding: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        table th { background: #333; color: white; }
        h3 { margin-top: 30px; }
    </style>
</head>
<body>

<header>
    <h1>📦 My Orders</h1>
</header>

<nav>
    <a href="index.php">🏠 Home</a>
    <a href="register.php">📝 Register</a>
    <a href="login.php">🔑 Login</a>
    <a href="products.php">🛍 Shop</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="my_orders.php">📦 My Orders</a>
</nav>

<div class="orders">
<?php
if ($orders->num_rows > 0) {
    while ($order = $orders->fetch_assoc()) {
        echo "<h3>Order #".$order['id']." | Customer: ".$order['customer_name']." | Total: ₹".$order['total']." | Payment: ".$order['payment_mode']."</h3>";

        // Fetch order items
        $items = $conn->query("SELECT * FROM order_items WHERE order_id=".$order['id']);

        echo "<table>";
        echo "<tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>";

        while ($row = $items->fetch_assoc()) {
            echo "<tr>
                    <td>".$row['product_name']."</td>
                    <td>".$row['quantity']."</td>
                    <td>₹".$row['price']."</td>
                    <td>₹".($row['price'] * $row['quantity'])."</td>
                  </tr>";
        }

        echo "</table><br>";
    }
} else {
    echo "<p>No orders found.</p>";
}
?>
</div>

</body>
</html>

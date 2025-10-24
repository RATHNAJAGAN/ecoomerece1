<?php
session_start();

// Connect to DB
$conn = new mysqli("localhost", "root", "", "ecommerce1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_GET['action']) && $_GET['action'] == "add") {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        // Get product from DB
        $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // If already in cart, increase qty
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = [
                    "name" => $product['name'],
                    "price" => $product['price'],
                    "image" => $product['image'],
                    "quantity" => 1
                ];
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == "remove") {
    $id = (int) $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Empty cart
if (isset($_GET['action']) && $_GET['action'] == "empty") {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php $grand_total = 0; ?>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><img src="<?php echo $item['image']; ?>" width="80"></td>
                    <td><?php echo $item['name']; ?></td>
                    <td>₹<?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo $item['price'] * $item['quantity']; ?></td>
                    <td><a href="cart.php?action=remove&id=<?php echo $id; ?>">Remove</a></td>
                </tr>
                <?php $grand_total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td>₹<?php echo $grand_total; ?></td>
                <td><a href="cart.php?action=empty">Empty Cart</a></td>
            </tr>
        </table>

        <!-- ✅ Links under the cart -->
        <br>
        <a href="/ecommerce1/index.php">← Continue Shopping</a> | 
        <a href="checkout.php">Proceed to Checkout</a>

    <?php else: ?>
        <p>Your cart is empty.</p>
        <!-- Even if cart empty, allow going back to shop -->
        <a href="/ecommerce1/index.php">← Continue Shopping</a>
    <?php endif; ?>

</body>
</html>

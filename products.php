<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "ecommerce1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        nav {
            margin: 20px;
            text-align: center;
        }
        nav a {
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            margin: 5px;
            background: #ddd;
            border-radius: 5px;
        }
        nav a:hover {
            background: #999;
            color: #fff;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
        }
        .product {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 15px;
            padding: 15px;
            width: 220px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .product h3 {
            margin: 10px 0;
        }
        .product p {
            color: green;
            font-weight: bold;
        }
        .product a {
            display: inline-block;
            padding: 8px 15px;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .product a:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Products</h1>
</header>

<nav>
    <a href="index.php">🏠 Home</a>
    <a href="register.php">📝 Register</a>
    <a href="login.php">🔑 Login</a>
    <a href="products.php">🛍 Shop</a>
    <a href="cart.php">🛒 Cart</a>
</nav>

<div class="products">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p>₹<?php echo $row['price']; ?></p>
                <a href="cart.php?action=add&id=<?php echo $row['id']; ?>">Add to Cart</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>

</body>
</html>

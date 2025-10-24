<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My E-commerce Website</title>
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

        /* Slider styles */
        .slider {
            position: relative;
            overflow: hidden;
            width: 100%;
            max-height: 400px;
            margin-bottom: 30px;
        }
        .slides {
            display: flex;
            width: 300%; /* 3 images */
            animation: scroll 15s infinite ease-in-out;
        }
        .slides img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        @keyframes scroll {
            0%, 20% { transform: translateX(0%); }
            40%, 60% { transform: translateX(-100%); }
            80%, 100% { transform: translateX(-200%); }
        }
        .offer {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,0,0,0.7);
            color: white;
            padding: 20px 40px;
            font-size: 28px;
            font-weight: bold;
            border-radius: 10px;
            animation: blink 2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .content {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to My E-commerce Website</h1>
</header>

<nav>
    <a href="index.php">🏠 Home</a>
    <a href="register.php">📝 Register</a>
    <a href="login.php">🔑 Login</a>
    <a href="products.php">🛍 Shop</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="my_orders.php">📦 My Orders</a>
</nav>

<!-- Slider Section -->
<div class="slider">
    <div class="slides">
        <img src="https://source.unsplash.com/1600x400/?shopping" alt="Banner 1">
        <img src="https://source.unsplash.com/1600x400/?sale" alt="Banner 2">
        <img src="https://source.unsplash.com/1600x400/?fashion" alt="Banner 3">
    </div>
    <div class="offer">🔥 Hurry Up! 50% OFF 🔥</div>
</div>

<!-- Content Section -->
<div class="content">
    <h2>Hello <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest"; ?>!</h2>
    <p>Browse our products and start shopping today.</p>
    <a class="btn" href="products.php">Start Shopping</a>
</div>

</body>
</html>

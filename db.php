<?php
$host = "localhost";
$user = "root";   // default XAMPP MySQL user
$pass = "";       // default password is empty
$dbname = "ecommerce";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

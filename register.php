<?php
// register.php (fixed to use `username` instead of `name`)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($username === '' || $email === '' || $password === '') {
        $error = 'Please fill all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {
        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email already registered.';
        } else {
            // Insert new user (note: using `username` column)
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn,
                "INSERT INTO `users` (`username`, `email`, `password`) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param($insert, 'sss', $username, $email, $hashed);

            if (mysqli_stmt_execute($insert)) {
                mysqli_stmt_close($insert);
                mysqli_stmt_close($stmt);
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = 'Insert failed: ' . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
  <h2>Register</h2>
  <?php if ($error): ?>
    <div style="color:red;"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <form method="post" action="">
    <label>Username</label><br>
    <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"><br>
    <label>Email</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><br>
    <label>Password</label><br>
    <input type="password" name="password"><br><br>
    <button type="submit">Register</button>
  </form>
  <p><a href="login.php">Login</a></p>
</body>
</html>

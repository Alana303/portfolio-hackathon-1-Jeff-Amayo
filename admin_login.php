<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);

    // Fetch admin from database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_email = ?");
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($admin_password, $admin['admin_password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            // Redirect after successful login to home.html
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='./home.html';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again!');</script>";
        }
    } else {
        echo "<script>alert('Admin not found. Please sign up first!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="login-container">
        <form action="admin_login.php" method="POST">
            <h2>Admin Login</h2>
            <input type="email" name="admin_email" placeholder="Email" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="admin_signup.php">Sign Up</a></p>
    </div>
</body>
</html>

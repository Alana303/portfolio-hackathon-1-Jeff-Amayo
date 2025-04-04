<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($user_password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];

            // Redirect after successful login
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='./home.html';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again!');</script>";
        }
    } else {
        echo "<script>alert('User not found. Please sign up first!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
    <div class="login-container">
        <form action="user_login.php" method="POST">
            <h2>User Login</h2>
            <input type="email" name="user_email" placeholder="Email" required>
            <input type="password" name="user_password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="user_signup.php">Sign Up</a></p>
    </div>
</body>
</html>

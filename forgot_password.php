<?php
session_start();
require 'config.php';
require 'send_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST['user_email']);

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate reset token
        $reset_token = bin2hex(random_bytes(50));

        // Save the reset token in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE user_email = ?");
        $stmt->bind_param("ss", $reset_token, $user_email);
        if ($stmt->execute()) {
            // Send the reset password email
            send_reset_password_email($user_email, $reset_token);
            echo "<script>alert('Check your email for password reset instructions.'); window.location.href='user_login.php';</script>";
        }
    } else {
        echo "<script>alert('No account found with that email address.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <form action="forgot_password.php" method="POST">
        <h2>Forgot Password</h2>
        <input type="email" name="user_email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>

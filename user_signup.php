<?php
session_start();
require 'config.php';
require 'send_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please try another.');</script>";
    } else {
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password, email_verified, verification_token) VALUES (?, ?, ?, ?, ?)");
        $verification_token = bin2hex(random_bytes(50)); // Create unique verification token
        $email_verified = 0; // Set email_verified to 0 initially
        $stmt->bind_param("sssis", $user_name, $user_email, $hashed_password, $email_verified, $verification_token);
        if ($stmt->execute()) {
            // Send the verification email
            send_verification_email($user_email, $verification_token);
            echo "<script>alert('Signup successful! Redirecting to homepage...'); window.location.href='home.html';</script>";

        } else {
            echo "<script>alert('Error during signup. Please try again later.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <div class="signup-container">
        <form action="user_signup.php" method="POST">
            <h2>User Signup</h2>
            <input type="text" name="user_name" placeholder="Full Name" required>
            <input type="email" name="user_email" placeholder="Email" required>
            <input type="password" name="user_password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="user_login.php">Login</a></p>
    </div>
</body>
</html>

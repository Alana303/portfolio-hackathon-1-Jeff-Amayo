<?php
session_start();
require 'config.php';

if (isset($_GET['token'])) {
    $verification_token = $_GET['token'];

    // Verify the token in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $verification_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Update the user's email_verified status to 1 (verified)
        $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        if ($stmt->execute()) {
            echo "<script>alert('Your email has been verified successfully. You can now log in.'); window.location.href='user_login.php';</script>";
        } else {
            echo "<script>alert('Error in verification. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Invalid verification token.'); window.location.href='user_signup.php';</script>";
    }
}
?>

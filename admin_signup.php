<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = trim($_POST['admin_name']);
    $admin_email = trim($_POST['admin_email']);
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT); // Hashing the password

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM admins WHERE admin_email = ?");
    $check_email->bind_param("s", $admin_email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Try another!');</script>";
    } else {
        // Insert admin into database
        $stmt = $conn->prepare("INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $admin_name, $admin_email, $admin_password);

        if ($stmt->execute()) {
            echo "<script>alert('Admin registered successfully! Redirecting to login...'); window.location.href='admin_login.php';</script>";
        } else {
            echo "<script>alert('Error: Could not register admin. Try again!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <div class="signup-container">
        <form action="admin_signup.php" method="POST">
            <h2>Admin Signup</h2>
            <input type="text" name="admin_name" placeholder="Full Name" required>
            <input type="email" name="admin_email" placeholder="Email" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="admin_login.php">Login</a></p>
    </div>
</body>
</html>

<?php
session_start();
require 'config.php';

if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];

    // Verify the reset token
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = trim($_POST['new_password']);
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in the database
            $stmt = $conn->prepare("UPDATE users SET user_password = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $hashed_password, $reset_token);
            if ($stmt->execute()) {
                echo "<script>alert('Password reset successful. You can now log in.'); window.location.href='user_login.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid reset token.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <form action="reset_password.php?token=<?php echo $_GET['token']; ?>" method="POST">
        <h2>Enter New Password</h2>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>

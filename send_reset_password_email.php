<?php
function send_reset_password_email($email, $token) {
    $subject = "Password Reset Request";
    $message = "
    <html>
    <head>
        <title>Reset Your Password</title>
    </head>
    <body>
        <p>Click the link below to reset your password:</p>
        <a href='http://localhost:85/Projects/Portfolio/reset_password.php?token=$token'>Reset Password</a>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <no-reply@yourdomain.com>' . "\r\n";

    mail($email, $subject, $message, $headers);
}
?>

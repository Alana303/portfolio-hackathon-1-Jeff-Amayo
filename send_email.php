<?php
function send_verification_email($email, $token) {
    $subject = "Email Verification";
    $message = "
    <html>
    <head>
        <title>Email Verification</title>
    </head>
    <body>
        <p>Click the link below to verify your email:</p>
        <a href='http://localhost:85/Projects/Portfolio/verify_email.php?token=$token'>Verify Email</a>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <no-reply@yourdomain.com>' . "\r\n";

    mail($email, $subject, $message, $headers);
}
?>

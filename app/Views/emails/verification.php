<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
</head>
<body>
    <h2>Welcome to SKYBIRD HOTEL!</h2>
    <p>Dear <?= $name ?>,</p>
    <p>Thank you for registering. Your verification code is:</p>
    <h3 style="background: #f4f4f4; padding: 10px; display: inline-block;">
        <?= $verificationCode ?>
    </h3>
    <p>Enter this code on the verification page to complete your registration.</p>
    <p>If you didn't create an account, please ignore this email.</p>
    <br>
    <p>Best regards,<br>SKYBIRD HOTEL Team</p>
</body>
</html>
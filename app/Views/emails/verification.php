<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .verification-code { 
            background: #3498db; 
            color: white; 
            padding: 15px; 
            font-size: 24px; 
            font-weight: bold; 
            text-align: center; 
            margin: 20px 0;
            border-radius: 5px;
            letter-spacing: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SkyBird Hotel</h1>
            <p>Email Verification</p>
        </div>
        
        <div class="content">
            <h2>Hello <?= esc($userName) ?>,</h2>
            <p>Thank you for registering with SkyBird Hotel. To complete your registration, please use the following verification code:</p>
            
            <div class="verification-code">
                <?= esc($verificationCode) ?>
            </div>
            
            <p>Alternatively, click the button below to verify your email:</p>
            <a href="<?= base_url('auth/verify') ?>" class="button">Verify Now</a>
            
            <p>This code will expire in 30 minutes.</p>
            <p>If you didn't create an account with us, please ignore this email.</p>
            
            <p>Best regards,<br>The SkyBird Hotel Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; <?= $year ?> SkyBird Hotel. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
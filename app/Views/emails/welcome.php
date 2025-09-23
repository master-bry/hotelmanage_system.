<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SkyBird Hotel</h1>
            <p>Welcome Aboard!</p>
        </div>
        
        <div class="content">
            <h2>Welcome to SkyBird Hotel, <?= esc($userName) ?>! 🎉</h2>
            
            <p>We're excited to have you as part of our community. Your account has been successfully verified and you can now:</p>
            
            <ul>
                <li>Book rooms and manage your reservations</li>
                <li>Access exclusive member discounts</li>
                <li>Receive special offers and promotions</li>
                <li>Manage your profile and preferences</li>
            </ul>
            
            <p>Ready to book your first stay? <a href="http://localhost:8080/home">Start exploring our rooms</a>!</p>
            
            <p>If you have any questions, feel free to contact our support team.</p>
            
            <p>Best regards,<br>The SkyBird Hotel Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; <?= $year ?> SkyBird Hotel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
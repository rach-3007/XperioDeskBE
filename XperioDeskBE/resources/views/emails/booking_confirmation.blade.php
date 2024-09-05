<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { padding: 20px; }
        .header { background-color: #f5f5f5; padding: 10px; text-align: center; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>Thank you for booking with us. Your booking details are as follows:</p>
            <ul>
                <li>Booking ID: {{ $bookingId }}</li>
                <li>Date: {{ $date }}</li>
                <li>Time: {{ $time }}</li>
            </ul>
            <p>Best regards,<br>Xperio Desk</p>
        </div>
    </div>
</body>
</html>

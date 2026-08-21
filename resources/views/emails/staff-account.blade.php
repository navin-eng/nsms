<!DOCTYPE html>
<html>
<head>
    <title>Your Staff User ID & Password</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #0d6efd; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .credentials { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .button { display: inline-block; padding: 10px 20px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to {{ config('app.name') }}</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $staffName }}</strong>,</p>
            <p>An account has been generated for you to access the School Management System.</p>
            
            <div class="credentials">
                <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
                <p><strong>User ID (Email):</strong> {{ $emailId }}</p>
                <p><strong>Password:</strong> {{ $plainPassword }}</p>
            </div>
            
            <p>Please click the button below to log in. We highly recommend changing your password after your first login.</p>
            
            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Log In to System</a>
            </p>
            
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Your Account Credentials</title>
</head>
<body>
    <h2>Hello {{ $name }}!</h2>
    
    <p>Your account has been created successfully. Here are your login credentials:</p>
    
    <p>
        <strong>Email:</strong> {{ $email }}<br>
        <strong>Password:</strong> {{ $password }}<br>
        <strong>Domain:</strong> {{ $domain }}
    </p>
    
    <p>Please login at: <a href="https://{{ $domain }}">{{ $domain }}</a></p>
    
    <p>For security reasons, we recommend changing your password after your first login.</p>
    
    <p>If you have any questions, please don't hesitate to contact us.</p>
    
    <p>Best regards,<br>Your Application Team</p>
</body>
</html> 
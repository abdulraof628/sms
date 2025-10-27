<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .greeting {
            font-size: 18px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .credentials-box {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #1f2937;
            font-size: 16px;
        }
        .credential-item {
            margin: 12px 0;
            padding: 8px 0;
        }
        .credential-label {
            font-weight: 600;
            color: #6b7280;
            display: inline-block;
            width: 100px;
        }
        .credential-value {
            color: #1f2937;
            font-family: monospace;
            background-color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
        </div>

        <h1>Welcome to {{ config('app.name') }}! 🎉</h1>
        
        <p class="greeting">Hi {{ $tenantName }},</p>
        
        <p>
            We're excited to welcome you to <strong>{{ config('app.name') }}</strong>, your comprehensive school management solution. 
            Your tenant account has been successfully created and is ready to use.
        </p>

        <div class="credentials-box">
            <h3>🔐 Your Login Credentials</h3>
            <div class="credential-item">
                <span class="credential-label">Email:</span>
                <span class="credential-value">{{ $email }}</span>
            </div>
            <div class="credential-item">
                <span class="credential-label">Password:</span>
                <span class="credential-value">{{ $password }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
        </div>

        <div class="warning">
            <strong>⚠️ Security Reminder:</strong> For your security, please change your password immediately after your first login. 
            Keep your credentials safe and never share them with anyone.
        </div>

        <p><strong>What's Next?</strong></p>
        <ul>
            <li>Log in to your account using the credentials above</li>
            <li>Complete your tenant profile</li>
            <li>Add schools, classes, and students</li>
            <li>Invite team members to collaborate</li>
        </ul>

        <p>
            If you have any questions or need assistance getting started, our support team is here to help. 
            Feel free to reach out anytime.
        </p>

        <div class="footer">
            <p><strong>Need Help?</strong></p>
            <p>Contact us at {{ config('mail.from.address') }}</p>
            <p style="margin-top: 15px;">
                Warm regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Team</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
        }
        .info-value {
            color: #2d3748;
            text-align: right;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
        }
        .highlight {
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to {{ config('app.name') }}!</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $staffName }}</strong>,
            </div>
            
            <p>
                We're thrilled to welcome you to our team! Your staff profile and user account have been successfully created in our School Management System.
            </p>
            
            <p>
                Below are your important details and login credentials:
            </p>
            
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Employee ID:</span>
                    <span class="info-value highlight">{{ $employeeId }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email Address:</span>
                    <span class="info-value">{{ $email }}</span>
                </div>
                @if($password)
                <div class="info-row">
                    <span class="info-label">Temporary Password:</span>
                    <span class="info-value highlight">{{ $password }}</span>
                </div>
                @endif
                @if($position)
                <div class="info-row">
                    <span class="info-label">Position:</span>
                    <span class="info-value">{{ $position }}</span>
                </div>
                @endif
                @if($department)
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value">{{ $department }}</span>
                </div>
                @endif
            </div>
            
            <p>
                <strong>Important:</strong> Please keep your Employee ID safe as you may need it for various system operations.
            </p>
            
            <div class="divider"></div>
            
            <p style="margin-bottom: 10px;">
                <strong>Next Steps:</strong>
            </p>
            <ul style="color: #4a5568; line-height: 2;">
                @if($password)
                <li><strong>Login with your email and temporary password above</strong></li>
                <li>Change your password immediately after first login</li>
                @else
                <li>You will receive your login credentials from your administrator</li>
                <li>Once you have your credentials, you can access the system</li>
                @endif
                <li>Your profile includes your work schedule, leave balances, and attendance tracking</li>
                <li>Contact your administrator if you have any questions</li>
            </ul>
            
            <div style="text-align: center; margin-top: 35px;">
                <a href="{{ $loginUrl }}" class="button">
                    Access the System
                </a>
            </div>
            
            <p style="color: #718096; font-size: 14px; margin-top: 30px;">
                If you have any questions or need assistance, please don't hesitate to reach out to your administrator or HR department.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>{{ config('app.name') }}</strong>
            </p>
            <p style="margin: 0;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>


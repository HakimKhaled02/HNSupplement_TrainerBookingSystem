<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Account Approved</title>
</head>
<body style="font-family: 'Poppins', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #00cc66; margin: 0; font-size: 28px; font-weight: 700;">Trainer Account Approved</h1>
        </div>

        <div style="background-color: #1a1a1a; color: #ffffff; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
            <p style="margin: 0 0 20px 0; font-size: 16px;">Hello {{ $user->name }},</p>
            
            <p style="margin: 0 0 20px 0; font-size: 16px;">
                Congratulations! Your trainer account has been approved. You can now log in to your trainer dashboard.
            </p>

            <div style="background-color: rgba(0, 204, 102, 0.1); border: 1px solid #00cc66; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h2 style="color: #00cc66; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Your Login Credentials:</h2>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Email:</strong> {{ $user->email }}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Password:</strong> <span style="background-color: rgba(0, 204, 102, 0.2); padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $password }}</span></p>
            </div>

            <p style="margin: 20px 0 0 0; font-size: 14px; color: #cccccc;">
                <strong>Important:</strong> Please change your password after your first login for security purposes.
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login') }}" style="display: inline-block; background-color: #00cc66; color: #000000; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">
                Login Now
            </a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; color: #666; font-size: 12px;">
            <p style="margin: 0;">If you have any questions, please contact our support team.</p>
            <p style="margin: 10px 0 0 0;">© {{ date('Y') }} HN Supplement. All rights reserved.</p>
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Application Status</title>
</head>
<body style="font-family: 'Poppins', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #dc3545; margin: 0; font-size: 28px; font-weight: 700;">Application Status Update</h1>
        </div>

        <div style="background-color: #1a1a1a; color: #ffffff; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
            <p style="margin: 0 0 20px 0; font-size: 16px;">Hello {{ $user->name }},</p>
            
            <p style="margin: 0 0 20px 0; font-size: 16px;">
                Thank you for your interest in becoming a trainer on our platform. After careful review of your application, we regret to inform you that we are unable to approve your trainer account at this time.
            </p>

            <div style="background-color: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px; color: #ffcccc;">
                    If you believe this decision was made in error or would like to reapply in the future, please don't hesitate to contact our support team. We appreciate your understanding.
                </p>
            </div>

            <p style="margin: 20px 0 0 0; font-size: 14px; color: #cccccc;">
                If you have any questions or concerns, please feel free to reach out to us.
            </p>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; color: #666; font-size: 12px;">
            <p style="margin: 0;">If you have any questions, please contact our support team.</p>
            <p style="margin: 10px 0 0 0;">© {{ date('Y') }} HN Supplement. All rights reserved.</p>
        </div>
    </div>
</body>
</html>


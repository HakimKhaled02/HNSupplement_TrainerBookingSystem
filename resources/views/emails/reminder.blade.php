<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #00cc66, #00aa55);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .booking-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background: #f5f5f5;
            border-left: 3px solid #00cc66;
        }
        .note {
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Booking Reminder</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $reminder->user->name }},</p>
        
        <p>This is a reminder about your upcoming training session:</p>
        
        <div class="booking-info">
            <h3 style="margin-top: 0; color: #00cc66;">Booking Details</h3>
            
            <div class="info-row">
                <strong>Trainer:</strong> {{ $reminder->booking->trainer->user->name }}
            </div>
            
            <div class="info-row">
                <strong>Period:</strong> 
                {{ \Carbon\Carbon::parse($reminder->booking->start_date)->format('M d, Y') }} - 
                {{ \Carbon\Carbon::parse($reminder->booking->end_date)->format('M d, Y') }}
            </div>
            
            <div class="info-row">
                <strong>Booking ID:</strong> #{{ $reminder->booking->id }}
            </div>
        </div>
        
        @if($reminder->note)
        <div class="note">
            <strong>Your Note:</strong><br>
            {{ $reminder->note }}
        </div>
        @endif
        
        <p>We hope you're looking forward to your training session!</p>
        
        <p>Best regards,<br>
        <strong>HN Supplement Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated reminder. Please do not reply to this email.</p>
    </div>
</body>
</html>


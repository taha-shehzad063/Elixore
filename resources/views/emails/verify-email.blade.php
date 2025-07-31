<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }
        .email-header {
            background: linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);
            padding: 25px;
            text-align: center;
            color: white;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            color: #71cd14;
            margin-bottom: 20px;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.6;
        }
        .btn-verify {
            display: inline-block;
            padding: 12px 25px;
            background-color: #71cd14;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 50px;
            margin: 30px 0;
            transition: background 0.3s ease;
        }
        .btn-verify:hover {
            background-color: #60b512;
        }
        .verify-link {
            word-break: break-all;
            font-size: 14px;
            background: #f3f3f3;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .email-footer {
            background-color: #eafbe2;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #d4ecd1;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Verify Your Email Address</h1>
        </div>

        <div class="email-body">
            <h2>Hello {{ $user->name }},</h2>
            <p>Thank you for creating an account with us. To complete your registration, please verify your email address by clicking the button below:</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="btn-verify">Verify Email</a>
            </div>

            <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
            <div class="verify-link">
               <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
            </div>

            <p style="margin-top: 20px;">If you did not sign up for an account, you can safely ignore this email.</p>
        </div>

        <div class="email-footer">
            Regards,<br>
            <strong>Your Store Team</strong><br>
            <small>Need help? Contact our support anytime.</small>
        </div>
    </div>
</body>
</html>

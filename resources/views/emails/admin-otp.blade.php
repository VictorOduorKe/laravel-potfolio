<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login Code</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fc; margin: 0; padding: 40px 20px; color: #161925;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e7f1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #212D52; padding: 28px 32px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px;">TheTechCraft Admin Security</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 36px 32px;">
                <p style="font-size: 16px; margin: 0 0 16px 0; color: #525a71;">Hello Victor,</p>
                <p style="font-size: 16px; margin: 0 0 24px 0; color: #161925;">Here is your one-time verification code to access your portfolio admin dashboard:</p>
                
                <div style="text-align: center; margin: 28px 0; padding: 20px; background-color: #f0f3f8; border-radius: 8px; border: 1px dashed #348bcb;">
                    <span style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #e47429; font-family: monospace;">{{ $otp }}</span>
                </div>

                <p style="font-size: 14px; margin: 0 0 12px 0; color: #525a71;">
                    This code will expire in <strong>{{ $expiresInMinutes }} minutes</strong>. If you did not initiate this login request, please verify your server credentials immediately.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 32px; background-color: #f8f9fc; border-top: 1px solid #e2e7f1; text-align: center;">
                <p style="font-size: 13px; margin: 0; color: #6f788f;">&copy; {{ date('Y') }} TheTechCraft &bull; Victor Oduor Portfolio</p>
            </td>
        </tr>
    </table>
</body>
</html>

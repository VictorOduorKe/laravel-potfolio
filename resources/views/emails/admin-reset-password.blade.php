<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TheTechCraft Admin Password Reset</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fc; margin: 0; padding: 40px 20px; color: #161925;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e7f1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #212D52; padding: 28px 32px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px;">TheTechCraft Admin Security</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 36px 32px;">
                <p style="font-size: 16px; margin: 0 0 16px 0; color: #525a71;">Hello Victor,</p>
                <p style="font-size: 15px; margin: 0 0 24px 0; color: #161925; line-height: 1.6;">
                    A request was received to reset the master password for your administrator account (<strong>{{ $admin->email }}</strong>).
                </p>

                <div style="text-align: center; margin: 32px 0;">
                    <a href="{{ $resetUrl }}" style="background-color: #e47429; color: #ffffff; padding: 14px 32px; font-size: 15px; font-weight: 700; text-decoration: none; border-radius: 8px; display: inline-block; box-shadow: 0 4px 10px rgba(228, 116, 41, 0.3);">
                        Reset Admin Password
                    </a>
                </div>

                <p style="font-size: 14px; margin: 0 0 12px 0; color: #525a71; line-height: 1.5;">
                    This password reset link is time-limited and will expire in <strong>{{ $expiresInMinutes }} minutes</strong>. It can only be used once.
                </p>
                <p style="font-size: 13px; margin: 0 0 20px 0; color: #6f788f; line-height: 1.5;">
                    If you did not initiate this password reset request, no further action is required. Your current password remains secure.
                </p>

                <div style="padding-top: 20px; border-top: 1px solid #e2e7f1;">
                    <p style="font-size: 12px; color: #8e98ac; margin: 0 0 8px 0; word-break: break-all;">
                        If the button above does not work, copy and paste this secure URL directly into your browser:
                    </p>
                    <div style="font-size: 11px; color: #348bcb; font-family: monospace; background: #f0f3f8; padding: 10px; border-radius: 6px; word-break: break-all;">
                        {{ $resetUrl }}
                    </div>
                </div>
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

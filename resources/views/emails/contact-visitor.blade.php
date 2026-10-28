<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank You for Reaching Out</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fc; margin: 0; padding: 40px 20px; color: #161925;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e7f1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #212D52; padding: 28px 32px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700;">Victor Oduor &bull; TheTechCraft</h1>
                <p style="color: #b8c6e8; margin: 6px 0 0 0; font-size: 14px;">Full Stack Engineer &amp; Systems Architect</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 36px 32px;">
                <p style="font-size: 16px; margin: 0 0 16px 0; color: #161925;">Hi {{ $contact->first_name }},</p>
                <p style="font-size: 15px; line-height: 1.6; margin: 0 0 20px 0; color: #525a71;">
                    Thank you for reaching out through my portfolio. I have received your message and will review it promptly. You can expect a response from me within 24 to 48 hours.
                </p>

                <div style="background-color: #f8f9fc; padding: 18px 20px; border-radius: 8px; border-left: 4px solid #348bcb; margin: 24px 0;">
                    <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #348bcb; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Your Message Summary:</span>
                    <p style="margin: 0; font-size: 14px; color: #161925; white-space: pre-wrap; line-height: 1.5;">{{ $contact->message }}</p>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #525a71; margin: 0 0 8px 0;">
                    In the meantime, feel free to check out my latest engineering roadmaps, articles, and GitHub repositories on <a href="{{ config('app.url') }}" style="color: #e47429; font-weight: 600; text-decoration: none;">TheTechCraft</a>.
                </p>
                <p style="font-size: 15px; font-weight: 600; color: #212D52; margin: 24px 0 0 0;">
                    Best regards,<br>
                    Victor Oduor
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 32px; background-color: #f8f9fc; border-top: 1px solid #e2e7f1; text-align: center;">
                <p style="font-size: 12px; margin: 0; color: #6f788f;">&copy; {{ date('Y') }} TheTechCraft &bull; Nairobi, Kenya</p>
            </td>
        </tr>
    </table>
</body>
</html>

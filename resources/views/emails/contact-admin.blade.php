<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8f9fc; margin: 0; padding: 40px 20px; color: #161925;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e7f1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #212D52; padding: 24px 32px;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700;">New Contact Form Submission</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <p style="font-size: 15px; margin: 0 0 20px 0; color: #525a71;">A visitor has submitted a new inquiry via your portfolio contact form:</p>

                <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8f9fc; border-radius: 8px; border: 1px solid #dee4ef; margin-bottom: 24px;">
                    <tr>
                        <td width="30%" style="font-weight: 600; color: #212D52;">Name:</td>
                        <td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #212D52;">Email:</td>
                        <td><a href="mailto:{{ $contact->email }}" style="color: #348bcb;">{{ $contact->email }}</a></td>
                    </tr>
                    @if($contact->phone_number)
                    <tr>
                        <td style="font-weight: 600; color: #212D52;">Phone:</td>
                        <td>{{ $contact->phone_number }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: 600; color: #212D52;">IP Address:</td>
                        <td>{{ $contact->ip_address }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #212D52;">Submitted At:</td>
                        <td>{{ $contact->created_at ? $contact->created_at->format('M d, Y H:i:s') : 'Just now' }}</td>
                    </tr>
                </table>

                <div style="background-color: #ffffff; padding: 16px; border-left: 4px solid #e47429; border-radius: 4px; border: 1px solid #e2e7f1; border-left-width: 4px;">
                    <strong style="color: #212D52; display: block; margin-bottom: 8px;">Message:</strong>
                    <p style="margin: 0; white-space: pre-wrap; font-size: 15px; line-height: 1.6; color: #161925;">{{ $contact->message }}</p>
                </div>

                <div style="margin-top: 28px; text-align: center;">
                    <a href="mailto:{{ $contact->email }}?subject={{ urlencode('Re: Portfolio Inquiry') }}" style="display: inline-block; background-color: #e47429; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 14px;">Reply to {{ $contact->first_name }}</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

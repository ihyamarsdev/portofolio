<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f5f7; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-top: 0;">New Contact Form Submission</h2>
        
        <p style="font-size: 16px; margin-bottom: 20px;">You have received a new message from the contact form on your portfolio website.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px 0; font-weight: bold; width: 120px; border-bottom: 1px solid #f1f5f9;">Sender:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">{{ $inbox->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #f1f5f9;">Email:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                    <a href="mailto:{{ $inbox->email }}" style="color: #3b82f6; text-decoration: none;">{{ $inbox->email }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #f1f5f9;">Subject:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">{{ $inbox->subject }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #f1f5f9; vertical-align: top;">Received:</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">{{ $inbox->created_at->format('M d, Y H:i:s') }} ({{ $inbox->created_at->diffForHumans() }})</td>
            </tr>
        </table>
        
        <div style="background-color: #f8fafc; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; font-style: italic; white-space: pre-wrap; line-height: 1.6;">{{ $inbox->message }}</div>
        
        <p style="font-size: 12px; color: #94a3b8; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 15px; text-align: center;">
            This email was sent automatically from your Portfolio website.
        </p>
    </div>
</body>
</html>

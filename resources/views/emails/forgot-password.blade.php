<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password - Caree Hotel</title>
</head>
<body style="background-color: #f5f5f4; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 40px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 36px 30px 20px 30px; background-color: #1c1917;">
                            <h1 style="color: #c9960c; font-size: 24px; font-weight: 600; margin: 0; letter-spacing: 2px; text-transform: uppercase;">Caree Hotel</h1>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 30px; color: #44403c;">
                            <h2 style="font-size: 20px; font-weight: 600; color: #1c1917; margin-top: 0; margin-bottom: 12px;">Password Reset Request</h2>
                            <p style="font-size: 14px; line-height: 1.6; color: #78716c; margin-bottom: 24px;">
                                Hello, we received a request to reset the password for your Caree Hotel account (<strong>{{ $email }}</strong>).
                            </p>
                            <p style="font-size: 14px; line-height: 1.6; color: #78716c; margin-bottom: 28px;">
                                Click the button below to choose a new password. This link will expire in 60 minutes.
                            </p>

                            <!-- Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" target="_blank" style="background: linear-gradient(135deg, #c9960c 0%, #e8b11c 100%); color: #ffffff; display: inline-block; padding: 14px 32px; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(184, 134, 11, 0.3);">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; line-height: 1.5; color: #a8a29e; margin-bottom: 0;">
                                If you did not request a password reset, no further action is required and your account remains secure.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f5f5f4; text-align: center; font-size: 11px; color: #a8a29e; border-top: 1px solid #e7e5e4;">
                            &copy; {{ date('Y') }} Caree Hotel. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
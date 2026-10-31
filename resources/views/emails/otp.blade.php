<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Code - Iswarya Silks</title>
</head>
<body style="font-family: 'Jost', Arial, sans-serif; background-color: #fdf7f3; margin: 0; padding: 40px 20px; color: #1a1a1a;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #fbebee;">
        <tr>
            <td style="background-color: #b1474b; padding: 28px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 2px; font-weight: 600;">ISWARYA SILKS</h1>
                <p style="color: #fef9e7; margin: 4px 0 0 0; font-size: 13px; letter-spacing: 1px;">PEELAMEDU, COIMBATORE • ஐஸ்வர்யா சில்க்ஸ்</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 36px 32px;">
                <h2 style="font-size: 18px; color: #b1474b; margin-top: 0;">{{ $purpose->label() }}</h2>
                <p style="font-size: 14px; line-height: 1.6; color: #575757;">Use the one-time verification code below to proceed with your luxury silk shopping experience.</p>
                
                <div style="background-color: #fbebee; border-radius: 8px; padding: 20px; text-align: center; margin: 28px 0; border: 1px dashed #b1474b;">
                    <span style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #8f1f23;">{{ $otp }}</span>
                </div>
                
                <p style="font-size: 13px; color: #8c8c8c; line-height: 1.5;">This code is valid for 10 minutes. If you did not request this verification code, please ignore this email or contact our concierge.</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8f6f4; padding: 20px; text-align: center; font-size: 12px; color: #8c8c8c; border-top: 1px solid #f0ece9;">
                &copy; {{ date('Y') }} Iswarya Silks, Peelamedu, Coimbatore. Handloom Certified Pure Silk.
            </td>
        </tr>
    </table>
</body>
</html>

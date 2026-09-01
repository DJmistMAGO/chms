<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $verification->valid_id_status === 'verified' ? 'ID Verified' : 'ID Verification Update' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background:#FFFDF0; font-family:'DM Sans', Arial, sans-serif; color:#3D3530;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#FFFDF0; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background:#FFFFFF; border:1px solid #FFE566; border-radius:20px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg, #FFD54F, #FFB300); padding:28px 32px; text-align:center;">
                            <img src="{{ asset('assets/images/chlogo.png') }}" alt="Caree Hotel" width="90" style="display:block; margin:0 auto 10px;">
                            <p style="margin:0; font-size:11px; letter-spacing:0.18em; text-transform:uppercase; color:rgba(28,28,30,0.6); font-weight:500;">
                                Caree Hotel
                            </p>
                        </td>
                    </tr>

                    {{-- Status banner --}}
                    <tr>
                        <td style="padding:32px 32px 0;">
                            <div style="display:inline-block; padding:6px 16px; border-radius:9999px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;
                                @if($verification->valid_id_status === 'verified')
                                    background:#E8F8EE; color:#1E8E4E;
                                @else
                                    background:#FDECEC; color:#D64545;
                                @endif
                            ">
                                @if($verification->valid_id_status === 'verified')
                                    ✓ ID Verified
                                @else
                                    ID Not Verified
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:16px 32px 8px;">
                            <h1 style="font-family:'Cormorant Garamond', Georgia, serif; font-size:26px; font-weight:700; color:#1C1C1E; margin:0 0 16px;">
                                {{ $verification->valid_id_status === 'verified' ? 'Your ID has been verified' : 'We need a clearer ID' }}
                            </h1>

                            <p style="font-size:14px; line-height:1.7; color:#3D3530; margin:0 0 16px;">
                                Hi {{ $verification->user->name }},
                            </p>

                            @if ($verification->valid_id_status === 'verified')
                                <p style="font-size:14px; line-height:1.7; color:#3D3530; margin:0 0 16px;">
                                    Good news! Your submitted valid ID has been verified. You're all set — no further action needed on your end, and your bookings will proceed as normal.
                                </p>
                            @else
                                <p style="font-size:14px; line-height:1.7; color:#3D3530; margin:0 0 16px;">
                                    We were unable to verify the ID you submitted.
                                </p>

                                @if ($verification->remarks)
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#FFF8D6; border:1px solid #FFE566; border-radius:12px; margin:0 0 16px;">
                                        <tr>
                                            <td style="padding:14px 18px;">
                                                <p style="margin:0 0 4px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#B89200;">
                                                    Reason
                                                </p>
                                                <p style="margin:0; font-size:14px; line-height:1.6; color:#3D3530;">
                                                    {{ $verification->remarks }}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                <p style="font-size:14px; line-height:1.7; color:#3D3530; margin:0 0 24px;">
                                    Please log in to your account and re-upload a clear, valid ID to continue.
                                </p>
                            @endif

                            {{-- CTA button --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:8px 0 28px;">
                                <tr>
                                    <td style="border-radius:14px; background:#FFD000;">
                                        <a href="{{ route('profile') }}" target="_blank" style="display:inline-block; padding:13px 28px; font-size:14px; font-weight:600; color:#1C1C1E; text-decoration:none; border-radius:14px;">
                                            {{ $verification->valid_id_status === 'verified' ? 'View my account' : 'Re-upload my ID' }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px;">
                            <div style="border-top:1px solid #FFE566;"></div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px 28px;">
                            <p style="font-size:12px; line-height:1.6; color:#7A6E68; margin:0;">
                                Thanks,<br>
                                <strong style="color:#3D3530;">{{ config('app.name') }}</strong>
                            </p>
                            <p style="font-size:11px; color:#A79E97; margin:16px 0 0;">
                                This is an automated message regarding your identity verification. If you believe this was sent in error, please contact our front desk.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

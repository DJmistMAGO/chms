<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Caree Hotel - Reservation Expiring Soon</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f6;
            margin: 0;
            padding: 0;
        }

        .mono {
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
        }

        .wrapper {
            background-color: #f4f4f6;
            padding: 40px 20px;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(30, 27, 75, 0.08);
        }

        /* ===== Ticket-stub header (urgency red/orange instead of yellow) ===== */
        .header {
            background-color: #fb923c;
            color: #1e1b4b;
            padding: 32px 30px 24px 30px;
            text-align: center;
            position: relative;
        }

        .header .eyebrow {
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.65;
            margin: 0 0 6px 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .header .ref {
            margin: 10px 0 0 0;
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
            font-size: 13px;
            font-weight: 600;
            background-color: rgba(30, 27, 75, 0.08);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .perforation {
            height: 20px;
            background-color: #fb923c;
            position: relative;
        }

        .perforation::before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 20px;
            background-image: radial-gradient(circle 10px, #f4f4f6 10px, transparent 11px);
            background-size: 28px 20px;
            background-position: -4px 0;
            background-repeat: repeat-x;
        }

        .content {
            padding: 34px 30px 10px 30px;
            background-color: #ffffff;
        }

        /* ===== Countdown block ===== */
        .countdown-row {
            text-align: center;
            margin-bottom: 30px;
        }

        .countdown-badge {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 16px 28px;
            background-color: #fff7ed;
            color: #9a3412;
            border-radius: 14px;
            border: 1px solid #fed7aa;
        }

        .countdown-badge .label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.75;
        }

        .countdown-badge .time {
            font-size: 26px;
            font-weight: 800;
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
        }

        .greeting {
            font-size: 15px;
            color: #444;
            margin: 0 0 4px 0;
        }

        .subtext {
            font-size: 14px;
            color: #777;
            margin: 0 0 8px 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #1a1a1a;
            margin-top: 28px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::after {
            content: "";
            flex: 1;
            border-top: 1px dashed #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        td {
            padding: 9px 0;
            font-size: 14.5px;
            vertical-align: top;
        }

        td.label {
            color: #888888;
            width: 50%;
        }

        td.value {
            color: #161616;
            font-weight: 600;
            text-align: right;
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
            font-size: 13.5px;
        }

        .remarks-box {
            background-color: #fff7ed;
            border-left: 3px solid #fb923c;
            padding: 14px 16px;
            margin-top: 26px;
            border-radius: 0 8px 8px 0;
        }

        .remarks-box h4 {
            margin: 0 0 4px 0;
            color: #1a1a1a;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .remarks-box p {
            margin: 0;
            color: #7c2d12;
            font-size: 14px;
        }

        .cta-row {
            text-align: center;
            margin-top: 26px;
        }

        .cta-button {
            display: inline-block;
            background-color: #1e1b4b;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 999px;
        }

        .note {
            margin-top: 22px;
            font-size: 13.5px;
            color: #888;
            text-align: center;
        }

        .footer-perforation {
            height: 20px;
            position: relative;
            background-color: #ffffff;
        }

        .footer-perforation::before {
            content: "";
            position: absolute;
            top: -10px;
            left: 0;
            width: 100%;
            height: 20px;
            background-image: radial-gradient(circle 10px, #f4f4f6 10px, transparent 11px);
            background-size: 28px 20px;
            background-position: -4px 0;
            background-repeat: repeat-x;
        }

        .footer {
            background-color: #fafafa;
            padding: 22px 20px 26px 20px;
            text-align: center;
            font-size: 11.5px;
            color: #aaaaaa;
        }

        .footer p {
            margin: 3px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">

            <!-- Header / ticket stub -->
            <div class="header">
                <p class="eyebrow">Action Needed</p>
                <h1>Caree Hotel</h1>
                <p class="ref">REF #{{ $booking->reference_number }}</p>
            </div>
            <div class="perforation"></div>

            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Dear Guest,</p>
                <p class="subtext">Your reservation is still pending and is about to expire. Please confirm soon to
                    avoid losing your room.</p>

                <div class="countdown-row">
                    <span class="countdown-badge">
                        <span class="label">Expires In</span>
                        <span class="time">{{ $remainingTime }}</span>
                    </span>
                </div>

                <!-- Schedule -->
                <div class="section-title">Stay Schedule</div>
                <table>
                    <tr>
                        <td class="label">Check-In</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y - h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Check-Out</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y - h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Guests</td>
                        <td class="value">{{ $booking->number_of_guests }}
                            {{ Str::plural('guest', $booking->number_of_guests) }}</td>
                    </tr>
                </table>

                <!-- Room Details -->
                <div class="section-title">Accommodation</div>
                <table>
                    <tr>
                        <td class="label">Room Type</td>
                        <td class="value">{{ $booking->room_type }} (No. {{ $booking->room->room_no ?? 'To be assigned' }})</td>
                    </tr>
                    <tr>
                        <td class="label">Floor</td>
                        <td class="value">{{ $booking->floor_level }}</td>
                    </tr>
                    <tr>
                        <td class="label">Reservation Expires</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->expires_at)->format('M d, Y - h:i A') }}</td>
                    </tr>
                </table>

                <div class="remarks-box">
                    <h4>Why am I getting this?</h4>
                    <p>Reservations must be confirmed before they expire, or the room will automatically be released
                        and made available to other guests.</p>
                </div>

                <p>To confirm your reservation, kindly pay at the counter the exact price displayed on your booking receipt.</p>


                <p class="note">Already confirmed? You can safely ignore this message.</p>
            </div>

            <div class="footer-perforation"></div>
            <div class="footer">
                <p>This is an automated notification regarding your booking.</p>
                <p>&copy; {{ date('Y') }} Caree Hotel. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

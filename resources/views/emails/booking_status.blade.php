<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Caree Hotel - Booking Status Update</title>
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

        /* ===== Ticket-stub header ===== */
        .header {
            background-color: #facc15;
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
        /* perforation strip simulating a torn ticket edge */
        .perforation {
            height: 20px;
            background-color: #facc15;
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

        .content { padding: 34px 30px 10px 30px; background-color: #ffffff; }

        /* ===== Status pill ===== */
        .status-row { text-align: center; margin-bottom: 30px; }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background-color: #fef9c3;
            color: #854d0e;
            font-weight: 700;
            border-radius: 999px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.6px;
            border: 1px solid #fde047;
        }
        .status-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ca8a04;
            display: inline-block;
        }
        .status-confirmed { background-color: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .status-confirmed .dot { background-color: #16a34a; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .status-cancelled .dot { background-color: #dc2626; }

        .greeting { font-size: 15px; color: #444; margin: 0 0 4px 0; }
        .subtext { font-size: 14px; color: #777; margin: 0 0 8px 0; }

        /* ===== Section titles ===== */
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

        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        td { padding: 9px 0; font-size: 14.5px; vertical-align: top; }
        td.label { color: #888888; width: 50%; }
        td.value {
            color: #161616;
            font-weight: 600;
            text-align: right;
            font-family: 'SF Mono', 'Consolas', 'Menlo', monospace;
            font-size: 13.5px;
        }

        /* ===== Receipt-style billing block ===== */
        .receipt-box {
            background-color: #fafafa;
            border-radius: 10px;
            padding: 18px 18px 6px 18px;
            margin-top: 4px;
        }
        .receipt-box td.value { font-size: 14px; }
        .total-row td {
            border-top: 1px dashed #d4d4d8;
            padding-top: 14px;
            margin-top: 6px;
        }
        .total-row td.label { color: #1a1a1a; font-size: 15px; font-weight: 700; }
        .total-row td.value {
            color: #854d0e;
            font-weight: 800;
            font-size: 19px;
        }

        .remarks-box {
            background-color: #fffbeb;
            border-left: 3px solid #facc15;
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
        .remarks-box p { margin: 0; color: #6b5c1f; font-size: 14px; }

        .note {
            margin-top: 28px;
            font-size: 13.5px;
            color: #888;
            text-align: center;
        }

        /* ===== Torn-edge footer, like the bottom of a receipt ===== */
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
        .footer p { margin: 3px 0; }
    </style>
</head>
<body>
    @php
        $nights = \Carbon\Carbon::parse($booking->check_in)
            ->diffInDays(\Carbon\Carbon::parse($booking->check_out));

        $roomRate = $booking->room_price;
        $addonsPerNight = $booking->micro_pricing_amount ?? 0;

        $roomSubtotal = $roomRate * $nights;
        $addonsSubtotal = $addonsPerNight * $nights;

        $grandTotal = $roomSubtotal + $addonsSubtotal;
    @endphp
    <div class="wrapper">
        <div class="container">

            <!-- Header / ticket stub -->
            <div class="header">
                <p class="eyebrow">Reservation Update</p>
                <h1>Caree Hotel</h1>
                <p class="ref">REF #{{ $booking->reference_number }}</p>
            </div>
            <div class="perforation"></div>

            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Dear Guest,</p>
                <p class="subtext">Thank you for choosing Caree Hotel. The status of your reservation has changed &mdash; here's the latest.</p>

                <div class="status-row">
                    <span class="status-badge
                        @if(strtolower($booking->status) == 'confirmed' || strtolower($booking->status) == 'approved') status-confirmed
                        @elseif(strtolower($booking->status) == 'cancelled' || strtolower($booking->status) == 'rejected') status-cancelled
                        @endif">
                        {{ $booking->status }}
                    </span>
                </div>

                <!-- Schedule -->
                <div class="section-title">Stay Schedule</div>
                <table>
                    <tr>
                        <td class="label">Check-In</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Check-Out</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Guests</td>
                        <td class="value">{{ $booking->number_of_guests }} {{ Str::plural('guest', $booking->number_of_guests) }}</td>
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
                        <td class="label">Ambiance</td>
                        <td class="value">{{ $booking->ambiance }}</td>
                    </tr>
                    <tr>
                        <td class="label">Food Package</td>
                        <td class="value">{{ $booking->food_package ?? 'Standard / None' }}</td>
                    </tr>
                </table>

                <div class="section-title">Billing Breakdown</div>
                    <div class="receipt-box">
                        <table>

                            <tr>
                                <td class="label">Length of Stay</td>
                                <td class="value">
                                    {{ $nights }}
                                    {{ Str::plural('night', $nights) }}
                                </td>
                            </tr>

                            <tr>
                                <td class="label">
                                    Room Rate
                                    <br>
                                    <small>
                                        ₱{{ number_format($roomRate,2) }}
                                        × {{ $nights }}
                                        {{ Str::plural('night',$nights) }}
                                    </small>
                                </td>

                                <td class="value">
                                    ₱{{ number_format($roomSubtotal,2) }}
                                </td>
                            </tr>

                            @if($addonsPerNight > 0)

                            <tr>
                                <td class="label">
                                    Add-ons / Micro Pricing
                                    <br>
                                    <small>
                                        ₱{{ number_format($addonsPerNight,2) }}
                                        × {{ $nights }}
                                        {{ Str::plural('night',$nights) }}
                                    </small>
                                </td>

                                <td class="value">
                                    ₱{{ number_format($addonsSubtotal,2) }}
                                </td>
                            </tr>

                            @endif

                            <tr class="total-row">
                                <td class="label">
                                    Total Amount
                                </td>

                                <td class="value">
                                    ₱{{ number_format($grandTotal,2) }}
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <!-- Remarks -->
                @if($booking->remarks)
                <div class="remarks-box">
                    <h4>Front Desk Remarks</h4>
                    <p>{{ $booking->remarks }}</p>
                </div>
                @endif

                <p class="note">Need to change something? Reach out to Caree Hotel guest management and we'll take care of it.</p>
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

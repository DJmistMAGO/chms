<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service | Caree Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'serif'],
                        sans: ['"DM Sans"', 'sans-serif']
                    },
                    colors: {
                        gold: {
                            50: '#fff9ee',
                            100: '#fef0d1',
                            200: '#f7d884',
                            300: '#efc75d',
                            400: '#dca81c',
                            500: '#c9960c',
                            600: '#b8860b',
                            700: '#8b6508',
                            900: '#3b2a0f'
                        },
                        ink: '#21160e',
                        ivory: '#f8f2ea'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-ivory text-ink font-sans antialiased">
    <main class="max-w-4xl mx-auto px-6 py-16 md:py-20">
        <a href="{{ route('landingpage') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gold-700 hover:text-gold-600 transition-colors mb-8">
            <span aria-hidden="true">←</span>
            Back to Home
        </a>

        <div class="rounded-[2rem] bg-white/90 border border-gold-200 shadow-[0_20px_50px_rgba(35,24,14,0.08)] p-8 md:p-12">
            <p class="text-xs uppercase tracking-[0.24em] text-gold-600 font-semibold mb-4">Terms of Service</p>
            <h1 class="font-display text-5xl md:text-6xl text-ink leading-none mb-8">Guest terms & conditions</h1>

            <div class="space-y-6 text-sm md:text-base leading-7 text-slate-700">
                <p>
                    These Terms of Service govern your use of the Caree Hotel reservation platform and any related guest services made available through our website or booking channels. By using our services, you agree to these terms.
                </p>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Booking and payment</h2>
                    <p>Reservation requests are subject to room availability and confirmation by Caree Hotel. Rates are subject to change without prior notice depending on room type, seasonality, promotions, and applicable taxes or service charges.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Guest responsibilities</h2>
                    <p>Guests are responsible for providing accurate reservation details, maintaining the confidentiality of their account credentials, and complying with all hotel policies during their stay. We reserve the right to refuse or cancel bookings that violate our terms or local regulations.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Identity verification</h2>
                    <p>For security and compliance, some bookings may require identity verification before confirmation or check-in. Guests must provide accurate information as requested by the hotel.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Cancellation and check-in</h2>
                    <p>Cancellation, modification, and arrival policies may vary by room type and booking promotion. Guests are encouraged to review the specific reservation details before confirming the booking.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Privacy and data use</h2>
                    <p>Caree Hotel processes personal data in accordance with our Privacy Policy. By using our services, you consent to the collection, use, and protection of your data as described in that policy.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Limitation of liability</h2>
                    <p>Caree Hotel strives to provide accurate information and quality service, but we are not liable for indirect, incidental, or consequential damages arising from the use of our services or inability to access them.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Changes to these terms</h2>
                    <p>We may revise these Terms of Service at any time. Continued use of our platform after changes are posted means you accept the revised terms.</p>
                </div>

                <p class="pt-4 border-t border-gold-200 text-gold-700 font-medium">
                    Effective date: September 2, 2026
                </p>
            </div>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy | Caree Hotel</title>
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
            <p class="text-xs uppercase tracking-[0.24em] text-gold-600 font-semibold mb-4">Privacy Policy</p>
            <h1 class="font-display text-5xl md:text-6xl text-ink leading-none mb-8">Your privacy matters</h1>

            <div class="space-y-6 text-sm md:text-base leading-7 text-slate-700">
                <p>
                    Caree Hotel values the trust you place in us when you book a room, create an account, or share information with our team. This Privacy Policy explains how we collect, use, protect, and disclose personal information in connection with our booking and guest services.
                </p>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Information we collect</h2>
                    <p>We may collect personal information such as your name, contact details, government-issued ID information for verification, reservation dates, payment references, and communication preferences when you use our service.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">How we use your information</h2>
                    <p>We use your information to process reservations, verify identity, provide guest support, communicate updates related to your stay, improve hotel services, and comply with applicable laws and security requirements.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Data protection</h2>
                    <p>We store sensitive booking and verification records securely and limit access to authorized personnel only. We use reasonable administrative, technical, and organizational safeguards to protect personal information against unauthorized access, loss, or misuse.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Sharing of information</h2>
                    <p>We do not sell personal data. We may share information with trusted service providers that support reservation processing, identity verification, and related operational functions, as required to deliver our services or comply with legal obligations.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Your choices</h2>
                    <p>You may update your account information and communication preferences through your profile where available. If you have questions about your information, contact our team and we will assist you as appropriate.</p>
                </div>

                <div>
                    <h2 class="font-display text-3xl text-ink mb-3">Changes to this policy</h2>
                    <p>We may update this Privacy Policy from time to time to reflect changes in our services or legal requirements. The latest version will be posted on this page with the effective date noted.</p>
                </div>

                <p class="pt-4 border-t border-gold-200 text-gold-700 font-medium">
                    Effective date: September 2, 2026
                </p>
            </div>
        </div>
    </main>
</body>
</html>

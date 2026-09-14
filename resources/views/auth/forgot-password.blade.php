<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Caree Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}" type="image/x-icon">
    <style>
        body { font-family: 'DM Sans', sans-serif; }

        .card-enter {
            animation: cardSlideUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes cardSlideUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #b8860b;
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.12);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #c9960c 0%, #e8b11c 100%);
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        }
        .btn-primary:hover {
            filter: brightness(1.08);
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.35);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }

        .overlay {
            background: linear-gradient(
                160deg,
                rgba(0,0,0,0.62) 0%,
                rgba(20,15,5,0.75) 55%,
                rgba(184,134,11,0.55) 100%
            );
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
      style="background-image: url('{{ asset('assets/images/ch2.png') }}')">

    <div class="overlay absolute inset-0"></div>

    <div class="relative z-10 w-full max-w-sm mx-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl px-8 py-9 card-enter">

            <div class="flex justify-center mb-4">
                <a href="{{ route('landingpage') }}">
                    <img src="{{ asset('assets/images/chlogo.png') }}" alt="Caree Hotel" class="h-12 object-contain drop-shadow hover:opacity-80 transition">
                </a>
            </div>

            <h2 class="font-display text-3xl font-semibold text-center text-stone-800 tracking-wide mb-1">Reset Password</h2>
            <p class="text-center text-stone-400 text-xs mb-7 font-light leading-relaxed">
                Enter your registered email address and we'll send you a link to reset your password.
            </p>

            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-xs">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                    <div class="flex items-center font-semibold text-red-800 mb-1 gap-1.5">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Unable to send reset link</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-stone-500 tracking-widest uppercase">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required
                        class="input-field w-full px-4 py-3 border border-stone-200 rounded-xl bg-stone-50 text-stone-800 placeholder-stone-300 text-sm"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="btn-primary w-full text-white py-3 rounded-xl font-medium tracking-wide text-sm shadow-md">
                    Send Reset Link
                </button>
            </form>

            <p class="text-center text-stone-400 text-xs mt-6">
                Remembered your password?
                <a href="{{ route('login') }}" class="text-amber-600 font-medium hover:text-amber-700 transition">Back to Sign In</a>
            </p>
        </div>
    </div>
</body>
</html>

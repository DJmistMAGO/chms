<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Caree Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}" type="image/x-icon">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        /* h1, h2, h3, .font-display { font-family: 'Cormorant Garamond', serif; } */

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

        .btn-google {
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
        }
        .btn-google:hover {
            background: #f9f9f9;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        .overlay {
            background: linear-gradient(
                160deg,
                rgba(0,0,0,0.62) 0%,
                rgba(20,15,5,0.75) 55%,
                rgba(184,134,11,0.55) 100%
            );
        }

        .label-float { transition: color 0.2s; }
        input:focus + .label-float,
        input:not(:placeholder-shown) + .label-float {
            color: #b8860b;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center relative"
        style="background-image: url('{{ asset('assets/images/ch2.png') }}')">

    <div class="overlay absolute inset-0"></div>

    <div class="relative z-10 w-full max-w-sm mx-4">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl px-8 py-9 card-enter">

            {{-- Logo --}}
            <div class="flex justify-center mb-2">
                <a href="{{ route('landingpage') }}">
                    <img src="{{ asset('assets/images/chlogo.png') }}" alt="Caree Hotel" class="h-14 object-contain drop-shadow hover:opacity-80 transition">
                </a>
            </div>

            <h2 class="font-display text-3xl font-semibold text-center text-stone-800 tracking-wide mb-1">Welcome Back</h2>
            <p class="text-center text-stone-400 text-sm mb-7 font-light">Sign in to your Caree Hotel account</p>

            @if ($errors->has('deactivated') || $errors->has('inactive'))
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                    {{ $errors->first('deactivated') ?: $errors->first('inactive') }}
                </div>
            @endif



            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" data-confirm-leave>
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-stone-500 tracking-widest uppercase">Email</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        required autofocus autocomplete="off"
                        class="input-field w-full px-4 py-3 border border-stone-200 rounded-xl bg-stone-50 text-stone-800 placeholder-stone-300 text-sm"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-medium text-stone-500 tracking-widest uppercase">Password</label>
                        <a href="{{--  --}}" class="text-xs text-amber-600 hover:text-amber-700 transition">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            required
                            class="input-field w-full px-4 py-3 border border-stone-200 rounded-xl bg-stone-50 text-stone-800 placeholder-stone-300 text-sm pr-11"
                        >
                        <button type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="btn-primary w-full text-white py-3 rounded-xl font-medium tracking-wide text-sm shadow-md">
                    Sign In
                </button>
            </form>

            <div class="flex items-center my-5">
                <div class="flex-grow border-t border-stone-200"></div>
                <span class="mx-3 text-stone-400 text-xs tracking-widest uppercase">or</span>
                <div class="flex-grow border-t border-stone-200"></div>
            </div>

            <a href="{{ route('login.google') }}"
                class="btn-google flex items-center justify-center gap-3 border border-stone-200 bg-white py-3 rounded-xl text-stone-700 text-sm font-medium shadow-sm">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                Continue with Google
            </a>

            <p class="text-center text-stone-400 text-xs mt-6">
                Don't have an account?
                <a href="{{ route('signup') }}" class="text-amber-600 font-medium hover:text-amber-700 transition">Create one</a>
            </p>
        </div>
    </div>

    {{-- Password Toggle Script --}}
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden
                ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>`
                : `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        }
    </script>
</body>
</html>

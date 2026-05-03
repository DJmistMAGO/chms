<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Caree Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center bg-cover bg-center"  style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgb(255, 235, 82)), url('{{ asset('assets/images/ch2.png') }}')">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">

        <h2 class="text-2xl font-bold text-center mb-6">
            Login to <span class="text-yellow-500">Caree Hotel</span>
        </h2>

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            </div>

            <button type="submit"
                class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition">
                Login
            </button>
        </form>

        <div class="flex items-center my-6">
            <div class="flex-grow border-t"></div>
            <span class="mx-3 text-gray-400 text-sm">OR</span>
            <div class="flex-grow border-t"></div>
        </div>

        <a href="/auth/google"
            class="flex items-center justify-center gap-3 border py-2 rounded-lg hover:bg-gray-100 transition">

            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
            <span>Continue with Google</span>
        </a>

        <p class="text-center text-sm mt-6">
            Don't have an account?
            <a href="/register" class="text-yellow-500 font-medium">Sign up</a>
        </p>

    </div>

</body>
</html>

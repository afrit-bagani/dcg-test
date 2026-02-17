<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DCG Education</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5', // Indigo-600
                        primaryHover: '#4338CA', // Indigo-700
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="mb-6">
            <a href="/" class="text-3xl font-bold text-primary tracking-tighter">
                DCG<span class="text-gray-700">Education</span>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-xl border border-gray-100">

            <h2 class="text-center text-2xl font-semibold mb-2 text-gray-800">Welcome back</h2>
            <p class="text-center text-gray-500 mb-8 text-sm">Please sign in to your account</p>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out
                        @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out
                        @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary h-4 w-4">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <a class="text-sm text-gray-600 hover:text-primary underline decoration-transparent hover:decoration-current transition-all"
                        href="#">
                        Forgot password?
                    </a>
                </div>

                <div class="mb-4">
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Sign in
                    </button>
                </div>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="font-medium text-primary hover:text-primaryHover hover:underline">
                            Sign up now
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} DCG Education. All rights reserved.
        </div>
    </div>
</body>

</html>

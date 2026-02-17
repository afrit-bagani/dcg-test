<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - DCG Education</title>
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

            <h2 class="text-center text-2xl font-semibold mb-6 text-gray-800">Create your account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out
                        @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out
                        @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out
                        @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">Confirm
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary focus:ring-opacity-50 border p-2.5 outline-none transition duration-150 ease-in-out">
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="text-sm text-gray-600 hover:text-gray-900 underline decoration-indigo-200 decoration-2 underline-offset-2"
                        href="{{ route('login') }}">
                        Already registered Login here
                    </a>

                    <button type="submit"
                        class="ml-4 inline-flex items-center px-6 py-2.5 bg-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primaryHover active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                        Register
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} DCG Education. All rights reserved.
        </div>
    </div>
</body>

</html>

<x-app title="Home">

    <main class="w-screen h-screen flex-col justify-center items-center">

        <h1 class="text-red text-5xl ">Home Page</h1>
        <nav>
            @auth
                <a class='underline text-blue-500' href="{{ route('admin.dashboard') }}">Go to Dashboard</a>
            @else
                <a class='underline text-blue-500' href="{{ route('login') }}">Log in</a>
                <a class='underline text-blue-500' href="{{ route('register') }}">Register</a>
            @endauth
        </nav>
    </main>

</x-app>

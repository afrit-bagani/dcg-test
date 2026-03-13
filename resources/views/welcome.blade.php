<h1>Home Page</h1>

<nav>
    @auth
        <a href="{{ route('admin.dashboard') }}">Go to Dashboard</a>
    @else
        <a href="{{ route('login') }}">Log in</a>
        <a href="{{ route('register') }}">Register</a>
    @endauth
</nav>

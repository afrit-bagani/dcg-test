<h1>Home Page</h1>

<nav>
    @auth
        <a href="{{ route('batches.index') }}">Go to Batch</a>
    @else
        <a href="{{ route('login') }}">Log in</a>
        <a href="{{ route('register') }}">Register</a>
    @endauth
</nav>

<form method="POST" action="/login">
    @csrf
    <h2>Login</h2>

    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">

    <label>
        <input type="checkbox" name="remember"> Ingat aku!
    </label>

    <button type="submit">Login</button>

    <hr>

    <a href="/auth/google">Login with Google</a>

    <br>
    <a href="{{ route('password.request') }}">Lupa Password?</a>
</form>
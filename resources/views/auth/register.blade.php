<form method="POST" action="/register">
    @csrf
    <h2>Register</h2>

    <input type="text" name="username" placeholder="Username">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password">

    <button type="submit">Daftar</button>

    <hr>

    <a href="/auth/google">Login with Google</a>
</form>
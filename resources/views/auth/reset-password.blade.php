<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AGRIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

<div class="bg-white w-full max-w-md p-8 rounded-lg shadow relative">

    <h2 class="text-2xl font-bold text-center mb-6">
        Reset Password
    </h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- PASSWORD BARU -->
        <div class="mb-5 relative">
            <input type="password" name="password" id="password" placeholder="Password Baru" class="w-full border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2 pr-10">
            <span onclick="togglePassword('password','eye1')" class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                <i id="eye1" class="fa-solid fa-eye"></i>
            </span>

            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6 relative">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" class="w-full border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2 pr-10">
            <span onclick="togglePassword('password_confirmation','eye2')"
                  class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                <i id="eye2" class="fa-solid fa-eye"></i>
            </span>
        </div>

        <button type="submit" class="w-full bg-[#58CC02] hover:bg-green-600 text-white py-3 rounded-full text-lg cursor-pointer transition">
            Reset Password
        </button>
    </form>

</div>

<script>
    function togglePassword(fieldId, eyeId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(eyeId);
        if (field.type === "password") {
            field.type = "text";
            eye.classList.remove("fa-eye");
            eye.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            eye.classList.remove("fa-eye-slash");
            eye.classList.add("fa-eye");
        }
    }
</script>

</body>
</html>

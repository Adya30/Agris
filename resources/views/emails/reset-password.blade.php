<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password AGRIS</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:40px;">

    <div style="max-width:500px; margin:auto; background:white; padding:30px; border-radius:10px;">

        <div style="text-align:center;">
            <h2 style="color:#58CC02;">Reset Password AGRIS</h2>
        </div>

        <p>Halo {{ $user->namaLengkap }},</p>

        <p>Kami menerima permintaan untuk mereset password akun AGRIS Anda.</p>

        <div style="text-align:center; margin:30px 0;">
            <a href="{{ $url }}"
               style="background:#58CC02; color:white; padding:12px 20px; border-radius:5px; text-decoration:none;">
                Reset Password
            </a>
        </div>

        <p>Link ini hanya berlaku selama <strong>5 menit</strong>.</p>

        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

        <hr>

        <p style="font-size:12px; color:#888;">
            © {{ date('Y') }} AGRIS. All rights reserved.
        </p>

    </div>

</body>
</html>

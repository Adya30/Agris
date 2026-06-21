<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AGRIS</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: 'Segoe UI', Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">

                    <tr>
                        <td style="background:white; border-radius:8px; padding:36px 32px;">

                            <h2 style="margin:0 0 8px; font-size:20px; font-weight:700; color:#222;">Reset Password</h2>
                            <p style="margin:0 0 24px; font-size:14px; color:#666;">
                                Halo <?php echo e($user->namaLengkap); ?>, kami menerima permintaan reset password untuk akun AGRIS Anda. Klik tombol di bawah untuk membuat password baru:
                            </p>

                            <div style="text-align:center; margin:0 0 24px;">
                                <a href="<?php echo e($url); ?>" style="display:inline-block; background:#58CC02; color:white; font-size:14px; font-weight:600; padding:12px 32px; border-radius:6px; text-decoration:none;">
                                    Reset Password Saya
                                </a>
                            </div>

                            <p style="margin:0 0 8px; font-size:13px; color:#666; line-height:1.6;">
                                Link berlaku selama <strong>5 menit</strong>. Setelah itu, Anda perlu meminta link baru.
                            </p>
                            <p style="margin:0; font-size:13px; color:#999; line-height:1.6;">
                                Jika Anda tidak meminta reset password, abaikan email ini.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="font-size:12px; color:#999; margin:0;">
                                © <?php echo e(date('Y')); ?> AGRIS
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH D:\project\Agris\resources\views/emails/reset-password.blade.php ENDPATH**/ ?>
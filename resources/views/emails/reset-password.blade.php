<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AGRIS</title>
</head>
<body style="margin:0; padding:0; background-color:#f0fdf4; font-family: 'Segoe UI', Arial, sans-serif; -webkit-font-smoothing:antialiased;">

    <!-- Outer Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0fdf4; padding:40px 16px;">
        <tr>
            <td align="center">

                <!-- Email Container -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

                    <!-- Header / Logo -->
                    <tr>
                        <td align="center" style="padding-bottom:32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#0f8629; width:56px; height:56px; border-radius:16px; text-align:center; vertical-align:middle;">
                                        <span style="color:white; font-size:24px; font-weight:800;">A</span>
                                    </td>
                                    <td style="padding-left:12px; vertical-align:middle;">
                                        <span style="font-size:28px; font-weight:800; color:#0f8629; letter-spacing:-0.5px;">AGRIS</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Card -->
                    <tr>
                        <td style="background:white; border-radius:24px; padding:40px 36px; box-shadow:0 4px 24px rgba(0,0,0,0.06);">

                            <!-- Badge -->
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:999px; padding:6px 16px;">
                                        <span style="font-size:11px; font-weight:700; color:#16a34a; text-transform:uppercase; letter-spacing:1px;">Keamanan Akun</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Title -->
                            <h1 style="text-align:center; font-size:24px; font-weight:800; color:#1e293b; margin:0 0 8px; line-height:1.3;">
                                Reset Password
                            </h1>
                            <p style="text-align:center; font-size:14px; color:#94a3b8; margin:0 0 28px;">
                                Kami menerima permintaan reset password untuk akun Anda
                            </p>

                            <!-- Greeting -->
                            <p style="font-size:15px; color:#334155; margin:0 0 16px; line-height:1.7;">
                                Halo <strong style="color:#0f8629;">{{ $user->namaLengkap }}</strong>,
                            </p>

                            <!-- Body -->
                            <p style="font-size:15px; color:#475569; margin:0 0 28px; line-height:1.7;">
                                Seseorang baru saja meminta untuk mereset password akun AGRIS Anda. Jika ini memang Anda, klik tombol di bawah untuk membuat password baru:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display:inline-block; background:linear-gradient(135deg, #58CC02, #0f8629); color:white; font-size:15px; font-weight:700; padding:14px 40px; border-radius:14px; text-decoration:none; box-shadow:0 4px 14px rgba(88,204,2,0.35); letter-spacing:0.3px;">
                                            Reset Password Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
                                <tr>
                                    <td style="background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:16px 20px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align:top; padding-right:12px;">
                                                    <span style="font-size:18px;">⚠️</span>
                                                </td>
                                                <td style="vertical-align:top;">
                                                    <p style="font-size:13px; color:#92400e; margin:0; line-height:1.6;">
                                                        Link ini hanya berlaku selama <strong>5 menit</strong>. Setelah itu, Anda perlu meminta link baru.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Text -->
                            <p style="font-size:13px; color:#94a3b8; margin:0; line-height:1.7; padding:20px 0 0; border-top:1px solid #f1f5f9;">
                                Jika Anda tidak meminta reset password, <strong style="color:#64748b;">abaikan email ini</strong>. Password Anda tidak akan berubah.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:32px;">
                            <p style="font-size:12px; color:#94a3b8; margin:0 0 4px;">
                                © {{ date('Y') }} <strong style="color:#64748b;">AGRIS</strong> — PT Surya Kencana Agrifarm Sejahtera
                            </p>
                            <p style="font-size:11px; color:#cbd5e1; margin:0;">
                                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Email Container -->

            </td>
        </tr>
    </table>

</body>
</html>

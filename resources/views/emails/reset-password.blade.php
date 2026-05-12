<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">

    <div
        style="max-width:600px;margin:40px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.08);">

        <!-- HEADER -->
        <div style="background:#7367f0; padding:25px 20px; text-align:center;">
            <h1 style="margin:0; color:#ffffff; font-size:22px; letter-spacing:1px;">
                Rose Massage
            </h1>
            <p style="margin:5px 0 0; color:#e8e7ff; font-size:13px;">
                Wellness • Relaxation • Care
            </p>
        </div>

        <!-- BODY -->
        <div style="padding:30px; color:#333333;">

            <h2 style="margin-top:0; font-size:18px; color:#222;">
                Password Reset Request
            </h2>

            <p style="font-size:14px; line-height:1.6;">
                Hello {{ $user->name ?? 'Valued Guest' }},
            </p>

            <p style="font-size:14px; line-height:1.6;">
                We received a request to reset the password for your Rose Massage account.
                If you made this request, please click the button below to continue.
            </p>

            <!-- BUTTON -->
            <div style="text-align:center; margin:30px 0;">
                <a href="{{ $url }}"
                    style="background:#7367f0;color:#ffffff;text-decoration:none;padding:12px 26px;border-radius:6px;font-size:14px;display:inline-block;">
                    Reset Password
                </a>
            </div>

            <!-- EXPIRY NOTE -->
            <p style="font-size:13px; color:#666; line-height:1.6;">
                This password reset link will expire in <strong>60 minutes</strong> for your security.
            </p>

            <!-- IGNORE NOTE -->
            <p style="font-size:13px; color:#666; line-height:1.6; margin-top:20px;">
                If you did not request this change, you can safely ignore this email — no action will be taken.
            </p>

        </div>

        <!-- FOOTER -->
        <div style="background:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#888;">
            © {{ date('Y') }} Rose Massage. All rights reserved.
        </div>

    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin:0; padding:0; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; background-color:#f7f9fc; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding:20px 10px;">
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05);">

                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="background-color:#314b6f; padding:30px 20px;">
                            <img src="{{ asset('img/company-logo-white.png') }}" alt="Company Logo" style="max-width:200px; height:auto;">
                            <h1 style="color:#ffffff; font-size:28px; font-weight:700; margin:30px 0 0;">Email Verification</h1>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td align="center" style="padding:40px 20px; color:#333333; line-height:1.6;">
                            <p style="font-size:16px; margin:0 0 25px;">
                                Welcome to our platform! Please click the button below to verify your email address and activate your account.
                            </p>

                            <!-- BUTTON (inline styles for full compatibility) -->
                            <a href="{{$details['verification_link']}}"
                                target="_blank"
                                style="display:inline-block; background-color:#007bff; color:#ffffff !important;
                        text-decoration:none; padding:15px 30px; border-radius:5px;
                        font-size:16px; font-weight:bold; letter-spacing:0.5px; text-align:center;">
                                Verify My Email
                            </a>

                            <!-- FALLBACK TEXT LINK -->
                            <p style="font-size:14px; color:#666666; margin-top:30px;">
                                If the button above doesn’t work, copy and paste this link into your browser:<br>
                                <a href="{{$details['verification_link']}}" target="_blank" style="color:#007bff; word-break:break-all;">
                                    {{$details['verification_link']}}
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="padding:30px 20px; font-size:12px; color:#888888; border-top:1px solid #e9ecef;">
                            <p style="margin:0 0 10px;">If you did not sign up for this, please ignore this email.</p>
                            <p style="margin:0;">&copy; 2025 ITinker. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>





{{--
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Your Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f7f9fc;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .header-logo {
            text-align: center;
            padding: 5px;
            padding-bottom: 50px;
            background-color: #314b6f;
        }

        .header-logo img {
            max-width: 200px;
            height: auto;
        }

        .hero-section {
            background-color: #314b6f;
            color: #ffffff;
            text-align: center;
            padding: 50px 20px;
        }

        .hero-section h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .content {
            padding: 40px 20px;
            text-align: center;
            color: #333333;
            line-height: 1.6;
        }

        .content p {
            font-size: 16px;
            margin: 0 0 25px;
        }

        .button-link {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .footer {
            text-align: center;
            padding: 30px 20px;
            font-size: 12px;
            color: #888888;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>

<body>
    <div class="email-container">

        <div class="hero-section">
            <div class="header-logo">
                <img src="{{ asset('img/company-logo-white.png') }}" alt="Company Logo">
</div>
<h1>Email Verification</h1>
</div>
<div class="content">
    <p>Welcome to our platform! Please click the button below to verify your email address and activate your account.</p>
    <a href="{{$details['verification_link']}}" class="button-link">Verify My Email</a>
</div>
<div class="footer">
    <p>If you did not sign up for this, please ignore this email.</p>
    <p>&copy; 2025 ITinker. All rights reserved.</p>
</div>
</div>
</body>

</html>

--}}
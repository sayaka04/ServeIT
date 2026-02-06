<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Message</title>
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
            border: 1px solid #e0e0e0;
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


        .content p {
            font-size: 16px;
            margin: 0 0 25px;
        }

        .content {
            padding: 40px 20px;
            text-align: left;
            color: #333333;
            line-height: 1.6;
        }

        .content-left {
            text-align: left;
        }

        .content-center {
            text-align: center;
        }




        .button-container {
            text-align: center;
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
                <img src="{{ route('getFile2', 'img/company-logo-small-white.png') }}" alt="Company Logo">
            </div>
            <h1>New Message</h1>
        </div>
        <div class="content">
            <div class="content-left">
                <h3>{{$subject}}</h3>
                <p>{{$mailMessage}}</p>
            </div>
            <div class="button-container">
                <a href="{{$details['link']}}" class="button-link">Open Conversation</a>
            </div>
        </div>
        <div class="footer">
            <p>If you did not sign up for this, please ignore this email.</p>
            <p>&copy; 2025 ITinker. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
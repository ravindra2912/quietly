<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #FFC700 0%, #FFD700 100%);
            padding: 30px;
            text-align: center;
            color: #000000;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .email-body {
            padding: 40px 30px;
            color: #333333;
        }

        .email-body h2 {
            color: #333333;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            color: #666666;
            margin-bottom: 20px;
        }

        .otp-box {
            background: linear-gradient(135deg, #FFC700 0%, #FFD700 100%);
            color: #000000;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(255, 199, 0, 0.3);
        }

        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #999999;
            font-size: 14px;
        }

        .email-footer p {
            margin: 5px 0;
        }

        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ config('const.site_setting.name') }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
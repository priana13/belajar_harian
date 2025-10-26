<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #2563eb;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .message-content {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 20px 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                <strong>Halo {{ $user->name }},</strong>
            </div>
            
            <div class="message-content">
                {{ $emailMessage }}
            </div>
            
            <p style="margin-top: 30px; color: #6b7280;">
                Terima kasih,<br>
                <strong>{{ config('app.name') }}</strong>
            </p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            {{-- <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p> --}}
        </div>
    </div>
</body>
</html>
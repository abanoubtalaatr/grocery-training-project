<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $subject_title }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h1 style="font-size: 22px; margin-bottom: 16px;">{{ $subject_title }}</h1>

    <div style="white-space: pre-line;">{{ $body }}</div>

    <p style="margin-top: 24px;">
        Thanks,<br>
        {{ config('app.name') }}
    </p>
</body>
</html>

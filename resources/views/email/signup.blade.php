<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hi {{ $name }},
    <br>
    Your verification code : {{$verification_code}} for {{ getenv('APP_NAME') }}
    <br>
    If you didn't request, please ignore this email.
    <br/>
</div>

</body>
</html>
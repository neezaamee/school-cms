<!DOCTYPE html>
<html>
<head>
    <title>Your Account Credentials</title>
</head>
<body>
    <h2>Welcome to {{ config('app.name') }}, {{ $name }}!</h2>
    <p>Your staff account has been created successfully. Below are your login credentials:</p>
    <p>
        <strong>Email:</strong> {{ $email }}<br>
        <strong>Password:</strong> {{ $password }}
    </p>
    <p>You can log in here: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>
    <p>Please change your password after your first login for security reasons.</p>
    <br>
    <p>Best Regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>

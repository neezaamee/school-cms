<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ElafSchool</title>
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f9fafd; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #edf2f9; border-radius: 8px; overflow: hidden; margin-top: 40px; margin-bottom: 40px; }
        .header { background-color: #2c7be5; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .content { padding: 40px; color: #4d5969; line-height: 1.6; }
        .footer { background-color: #f9fafd; padding: 20px; text-align: center; font-size: 12px; color: #9da9bb; border-top: 1px solid #edf2f9; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #2c7be5; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: 600; margin-top: 20px; box-shadow: 0 4px 6px rgba(44, 123, 229, 0.15); transition: background-color 0.2s; }
        .credentials-box { background-color: #f1f6ff; border: 1px dashed #2c7be5; border-radius: 6px; padding: 20px; margin: 25px 0; }
        .credentials-box p { margin: 5px 0; font-size: 14px; }
        .credentials-label { font-weight: 700; color: #273444; min-width: 80px; display: inline-block; }
        .highlight { color: #2c7be5; font-weight: 700; }
        h2 { color: #273444; font-size: 18px; margin-top: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ElafSchool Management</h1>
        </div>
        <div class="content">
            <h2>Welcome aboard, {{ $user->name }}!</h2>
            <p>Congratulations! Your school, <strong>{{ $school->name }}</strong>, has been successfully registered on the ElafSchool platform.</p>
            <p>We are excited to help you transform your school's administration with our premium management tools.</p>
            
            <div class="credentials-box">
                <p><span class="credentials-label">Login URL:</span> <a href="{{ url('/login') }}" class="highlight">{{ url('/login') }}</a></p>
                <p><span class="credentials-label">Email:</span> <span class="highlight">{{ $user->email }}</span></p>
                <p><span class="credentials-label">Password:</span> <span class="highlight">{{ $password }}</span></p>
            </div>

            <p>For security reasons, we strongly recommend that you change your password immediately after your first login.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Login to Dashboard</a>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance setting up your school, please don't hesitate to reach out to our tech support team.</p>
            <p>Best regards,<br>The ElafTech Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ElafTech Solutions. All rights reserved.<br>
            Professional School Management Excellence.
        </div>
    </div>
</body>
</html>

<x-mail::message>
# Password Updated Successfully

Hello {{ $user->name }},

This is a confirmation that the password for your ElafSchool account (**{{ $user->email }}**) was recently changed.

If you did this, you can safely disregard this email.

If you did not change your password, please contact our support team immediately to secure your account.

<x-mail::button :url="config('app.url') . '/login'">
Login to Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>

<p>Hello {{ $user->name }},</p>
<p>Click the link below to verify your email address:</p>
<p><a href="{{ url('/api/verify-email/' . $user->verification_token) }}">Verify Email</a></p>

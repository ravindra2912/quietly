@include('emails.partials.header')

<h2>Welcome {{ $userName }}!</h2>

<p>Thank you for registering with {{ config('const.site_setting.name') }}. We're excited to have you on board!</p>

<p>Your account has been successfully created. You can now log in and start exploring all the features we have to offer.</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="{{ config('app.url') }}/login" style="background: linear-gradient(135deg, #FFC700 0%, #FFD700 100%); color: #000000; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
        Get Started
    </a>
</div>

<p>If you have any questions or need assistance, feel free to reach out to our support team.</p>

<div class="divider"></div>

<p style="font-size: 14px; color: #999999;">
    Best regards,<br>
    The {{ config('const.site_setting.name') }} Team
</p>

@include('emails.partials.footer')
@include('emails.partials.header')

<h2>Hello {{ $userName }}!</h2>

<p>We received a request to reset your password. Use the OTP code below to complete the password reset process:</p>

<!-- OTP Box -->
<div class="otp-box">
    {{ $otp }}
</div>

<p>This OTP is valid for <strong>10 minutes</strong> only. If you didn't request a password reset, please ignore this email or contact our support team if you have concerns.</p>

<!-- Warning Box -->
<div class="warning-box">
    <p><strong>⚠️ Security Notice:</strong> Never share this OTP with anyone. Our team will never ask for your OTP via phone or email.</p>
</div>

<div class="divider"></div>

<p style="font-size: 14px; color: #999999;">
    If you're having trouble with the password reset process, please contact our support team for assistance.
</p>

@include('emails.partials.footer')
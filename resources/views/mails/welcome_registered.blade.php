<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family:Arial,sans-serif;line-height:1.5;color:#222;">
    <p>{{ __('site.mail_welcome_greeting', ['name' => $user->name]) }}</p>
    <p>{{ __('site.mail_welcome_body') }}</p>
    <p style="margin-top:16px;color:#666;font-size:12px;">{{ __('site.mail_welcome_footer') }}</p>
</body>
</html>


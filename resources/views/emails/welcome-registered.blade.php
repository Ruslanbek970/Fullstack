<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #222;">
    <p>{{ __('site.mail_welcome_greeting', ['name' => $user->name]) }}</p>
    <p>{{ __('site.mail_welcome_body') }}</p>
    <p style="margin-top: 1.5rem; color: #666; font-size: 0.9rem;">{{ __('site.mail_welcome_footer') }}</p>
</body>
</html>

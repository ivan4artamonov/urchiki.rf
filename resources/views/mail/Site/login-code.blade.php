<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Код для входа</title>
</head>
<body style="margin:0;padding:24px;font-family:system-ui,sans-serif;background:#fafaf7;color:#1a1a1a;">
	<p style="margin:0 0 12px;font-size:15px;">Здравствуйте!</p>
	<p style="margin:0 0 20px;font-size:15px;line-height:1.5;">Ваш код для входа в {{ config('app.name') }}:</p>
	<p style="margin:0 0 24px;font-size:28px;font-weight:700;letter-spacing:0.2em;color:#b84030;">{{ $code }}</p>
	<p style="margin:0;font-size:13px;color:#5c5c5c;">Код действует 10 минут. Если вы не запрашивали вход, просто проигнорируйте это письмо.</p>
</body>
</html>

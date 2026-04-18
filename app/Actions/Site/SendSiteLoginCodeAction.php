<?php

namespace App\Actions\Site;

use App\Mail\SiteLoginCodeMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Отправка одноразового четырёхзначного кода на email для входа на сайт.
 */
class SendSiteLoginCodeAction
{
    /**
     * Генерирует код, сохраняет его хэш в кэше и отправляет письмо.
     *
     * @param  string  $email  Адрес получателя (как ввёл пользователь)
     * @return bool false при превышении лимита запросов кода
     */
    public function handle(string $email): bool
    {
        $to = trim($email);
        $normalized = mb_strtolower($to);
        $rateKey = 'site_login_send:'.$normalized;

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            return false;
        }

        $plainCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        Mail::to($to)->send(new SiteLoginCodeMail($plainCode));

        $otpKey = 'site_email_otp:'.$normalized;

        Cache::put($otpKey, [
            'hash' => Hash::make($plainCode),
            'fails' => 0,
        ], now()->addMinutes(10));

        RateLimiter::hit($rateKey, 3600);

        return true;
    }
}

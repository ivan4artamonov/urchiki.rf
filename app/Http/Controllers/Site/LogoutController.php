<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Auth;

/**
 * Завершает сессию пользователя с публичной части сайта и возвращает на предыдущую страницу.
 */
class LogoutController extends Controller
{
    /**
     * Выход из аккаунта и редирект на безопасный URL возврата.
     *
     * @param  Request  $request  HTTP-запрос выхода (сессия, заголовки)
     * @return RedirectResponse Ответ с редиректом на вычисленный URL
     *
     * @throws UrlGenerationException Если не удаётся сгенерировать URL резервной главной
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $returnUrl = $this->resolveReturnUrl($request);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to($returnUrl);
    }

    /**
     * Вычисляет URL возврата по Referer / previous с проверкой того же хоста (без внешних редиректов).
     *
     * @param  Request  $request  Текущий запрос (хост для сверки кандидата)
     * @return string Абсолютный URL предыдущей страницы на этом хосте или главная
     *
     * @throws UrlGenerationException Если не удаётся сгенерировать URL главной как запасной вариант
     */
    private function resolveReturnUrl(Request $request): string
    {
        $fallback = route('site.home');
        $candidate = url()->previous($fallback);

        if (! is_string($candidate) || $candidate === '') {
            return $fallback;
        }

        if (! $this->isSameHostUrl($request, $candidate)) {
            return $fallback;
        }

        return $candidate;
    }

    /**
     * Проверяет, что URL ведёт на тот же хост, что и текущий запрос.
     *
     * Исключения наружу не пробрасывает.
     *
     * @param  Request  $request  Текущий запрос (сверка хоста)
     * @param  string  $url  Кандидат на редирект (как правило абсолютный URL)
     * @return bool true, если хост совпадает с приложением
     */
    private function isSameHostUrl(Request $request, string $url): bool
    {
        $parts = parse_url($url);
        $host = $parts['host'] ?? null;

        return is_string($host) && $host === $request->getHost();
    }
}

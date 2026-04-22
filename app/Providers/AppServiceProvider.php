<?php

namespace App\Providers;

use App\View\Composers\AdminNavItemsComposer;
use App\View\Composers\GradeComposer;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Mailru\MailruExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\VKontakte\VKontakteExtendSocialite;
use SocialiteProviders\Yandex\YandexExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin', AdminNavItemsComposer::class);
        View::composer('components.site.footer', GradeComposer::class);

        Event::listen(SocialiteWasCalled::class, [VKontakteExtendSocialite::class, 'handle']);
        Event::listen(SocialiteWasCalled::class, [YandexExtendSocialite::class, 'handle']);
        Event::listen(SocialiteWasCalled::class, [MailruExtendSocialite::class, 'handle']);
    }
}

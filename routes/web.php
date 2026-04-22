<?php

use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Site\LogoutController as SiteLogoutController;
use App\Http\Controllers\Site\SocialAuthController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Faq\Create as FaqCreate;
use App\Livewire\Admin\Faq\Edit as FaqEdit;
use App\Livewire\Admin\Faq\Index as FaqIndex;
use App\Livewire\Admin\Grades\Edit as GradesEdit;
use App\Livewire\Admin\Grades\Index as GradesIndex;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Quarters\Edit as QuartersEdit;
use App\Livewire\Admin\Subjects\Create as SubjectsCreate;
use App\Livewire\Admin\Subjects\Edit as SubjectsEdit;
use App\Livewire\Admin\Subjects\Index as SubjectsIndex;
use App\Livewire\Admin\Tariffs\Create as TariffsCreate;
use App\Livewire\Admin\Tariffs\Edit as TariffsEdit;
use App\Livewire\Admin\Tariffs\Index as TariffsIndex;
use App\Livewire\Admin\Topics\Create as TopicsCreate;
use App\Livewire\Admin\Topics\Edit as TopicsEdit;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Worksheets;
use App\Livewire\Site\Account\Profile as AccountProfile;
use App\Livewire\Site\Faq as SiteFaq;
use App\Livewire\Site\Home;
use App\Livewire\Site\Hub as SiteHub;
use App\Livewire\Site\SiteAuth;
use Illuminate\Support\Facades\Route;

Route::name('site.')->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/faq', SiteFaq::class)->name('faq');
    Route::post('/logout', SiteLogoutController::class)->middleware('auth')->name('logout');

    Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
        Route::get('/profile', AccountProfile::class)->name('profile');
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', SiteAuth::class)->name('login');
    });

    Route::prefix('auth/social')
        ->name('social.')
        ->group(function () {
            Route::middleware('guest')->get('{provider}/redirect', [SocialAuthController::class, 'redirect'])
                ->name('redirect');

            Route::get('{provider}/callback', [SocialAuthController::class, 'callback'])
                ->name('callback');
        });

    /*
     * Каталог рабочих листов (хаб): один маршрут, три опциональных сегмента пути.
     *
     * Параметры намеренно названы нейтрально (slug1…slug3): первый сегмент по смыслу может быть
     * либо предметом, либо классом (параллелью). Конкретная интерпретация и ответ 404 выполняются
     * в Livewire-компоненте App\Livewire\Site\Hub::mount(), а не на уровне implicit route model binding.
     *
     * Ограничения ->where([...]) нужны, чтобы этот catch-all не перехватывал служебные URL вида
     * /__auth_site или пути с подчёркиванием и прочими символами вне набора [a-z0-9-].
     *
     * Варианты URL после разбора в хабе:
     * — /{предмет} — список классов для выбранного предмета;
     * — /{предмет}/{класс} — список тем предмета в контексте класса;
     * — /{предмет}/{класс}/{тема} — страница темы (конец ветки, дочерних ссылок нет);
     * — /{класс} (без slug2 и slug3) — СЕО-страница класса: список предметов со ссылками вида
     *   /{предмет}/{класс}, чтобы уточнить предмет, не оставаясь на обзоре «все предметы этого класса».
     *
     * Если один и тот же слаг существует и у предмета, и у класса, приоритет у предмета (не ломаем
     * старые ссылки «сначала предмет»). Лишние сегменты при режиме «только класс в slug1» дают 404.
     */
    Route::get('/{slug1}/{slug2?}/{slug3?}', SiteHub::class)
        ->where([
            'slug1' => '[a-z0-9-]+',
            'slug2' => '[a-z0-9-]+',
            'slug3' => '[a-z0-9-]+',
        ])
        ->name('hub');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', Login::class)->name('login');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/worksheets', Worksheets::class)->name('worksheets');
        Route::prefix('subjects')->name('subjects.')->group(function () {
            Route::get('/', SubjectsIndex::class)->name('index');
            Route::get('/create', SubjectsCreate::class)->name('create');
            Route::get('/{subject}/edit', SubjectsEdit::class)->name('edit');
        });
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', GradesIndex::class)->name('index');
            Route::get('/{grade}/edit', GradesEdit::class)->name('edit');
        });

        Route::prefix('quarters')->name('quarters.')->group(function () {
            Route::get('/{quarter}/edit', QuartersEdit::class)->name('edit');
        });

        Route::prefix('topics')->name('topics.')->group(function () {
            Route::get('/create/{subject}', TopicsCreate::class)->name('create');
            Route::get('/{topic}/edit', TopicsEdit::class)->name('edit');
        });
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UsersIndex::class)->name('index');
            Route::get('/create', UsersCreate::class)->name('create');
            Route::get('/{user}/edit', UsersEdit::class)->name('edit');
        });
        Route::prefix('faq')->name('faq.')->group(function () {
            Route::get('/', FaqIndex::class)->name('index');
            Route::get('/create', FaqCreate::class)->name('create');
            Route::get('/{faqItem}/edit', FaqEdit::class)->name('edit');
        });

        Route::prefix('tariffs')->name('tariffs.')->group(function () {
            Route::get('/', TariffsIndex::class)->name('index');
            Route::get('/create', TariffsCreate::class)->name('create');
            Route::get('/{tariff}/edit', TariffsEdit::class)->name('edit');
        });

        Route::post('/logout', AdminLogoutController::class)->name('logout');
    });
});

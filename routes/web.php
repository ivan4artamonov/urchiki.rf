<?php

use App\Http\Controllers\Admin\LogoutController as AdminLogoutController;
use App\Http\Controllers\Site\LogoutController as SiteLogoutController;
use App\Http\Controllers\Site\SiteSocialAuthController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Faq\Create as FaqCreate;
use App\Livewire\Admin\Faq\Edit as FaqEdit;
use App\Livewire\Admin\Faq\Index as FaqIndex;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Tariffs\Create as TariffsCreate;
use App\Livewire\Admin\Tariffs\Edit as TariffsEdit;
use App\Livewire\Admin\Tariffs\Index as TariffsIndex;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Worksheets;
use App\Livewire\Site\Account\Profile as AccountProfile;
use App\Livewire\Site\Faq as SiteFaq;
use App\Livewire\Site\Home;
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
            Route::middleware('guest')->get('{provider}/redirect', [SiteSocialAuthController::class, 'redirect'])
                ->name('redirect');

            Route::get('{provider}/callback', [SiteSocialAuthController::class, 'callback'])
                ->name('callback');
        });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', Login::class)->name('login');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/worksheets', Worksheets::class)->name('worksheets');
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

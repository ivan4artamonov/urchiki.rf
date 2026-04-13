<?php

use App\Http\Controllers\Admin\LogoutController;
use App\Livewire\Admin\Classes;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Faq;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Subscription;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Worksheets;
use App\Livewire\Site\Home;
use Illuminate\Support\Facades\Route;

Route::name('site.')->group(function () {
	Route::get('/', Home::class)->name('home');
});

Route::prefix('admin')->name('admin.')->group(function () {
	Route::middleware('guest')->group(function () {
		Route::get('/login', Login::class)->name('login');
	});

	Route::middleware('auth')->group(function () {
		Route::get('/', Dashboard::class)->name('dashboard');
		Route::get('/worksheets', Worksheets::class)->name('worksheets');
		Route::get('/classes', Classes::class)->name('classes');
		Route::get('/users', Users::class)->name('users');
		Route::get('/faq', Faq::class)->name('faq');
		Route::get('/subscription', Subscription::class)->name('subscription');
		Route::post('/logout', LogoutController::class)->name('logout');
	});
});

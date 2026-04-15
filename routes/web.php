<?php

use App\Http\Controllers\Admin\LogoutController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Faq;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Tariffs;
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

	Route::middleware(['auth', 'admin'])->group(function () {
		Route::get('/', Dashboard::class)->name('dashboard');
		Route::get('/worksheets', Worksheets::class)->name('worksheets');
		Route::get('/users', Users::class)->name('users');
		Route::get('/faq', Faq::class)->name('faq');
		Route::get('/tariffs', Tariffs::class)->name('tariffs');
		Route::post('/logout', LogoutController::class)->name('logout');
	});
});

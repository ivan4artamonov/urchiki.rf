<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Login;
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
	});
});

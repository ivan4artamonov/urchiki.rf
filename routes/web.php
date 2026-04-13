<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Site\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class);
Route::get('/admin', Dashboard::class);

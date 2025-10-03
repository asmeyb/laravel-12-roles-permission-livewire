<?php

use App\Livewire\BrandManagement;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use App\Livewire\CarManagement;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/user-management', UserManagement::class)->name('users.index');
    Route::get('/role-management', RoleManagement::class)->name('roles.index');
    Route::get('/car-management', CarManagement::class)->name('cars.index');
    Route::get('/brand-management', BrandManagement::class)->name('brands.index');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

<?php

use App\Livewire\BoardComponent;
use App\Livewire\BoardManagement;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('/boards', BoardManagement::class)
    ->middleware(['auth'])
    ->name('boards.table');

Route::get('boards/{board}', BoardComponent::class)
    ->middleware(['auth', 'verified'])
    ->name('boards.livewire');

Route::redirect('/dashboard', '/boards');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

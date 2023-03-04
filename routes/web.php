<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;

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

Route::post('/dashboard/login', [Dashboard\DashboardController::class, 'login'])->name('dashboard.login');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    // Dashboard home page
    Route::get('/', 'DashboardController@index')->name('dashboard.index');

    // Users management
    Route::get('/users', 'UserController@index')->name('dashboard.users.index');
    Route::get('/users/create', 'UserController@create')->name('dashboard.users.create');
    Route::post('/users', 'UserController@store')->name('dashboard.users.store');
    Route::get('/users/{user}', 'UserController@show')->name('dashboard.users.show');
    Route::get('/users/{user}/edit', 'UserController@edit')->name('dashboard.users.edit');
    Route::put('/users/{user}', 'UserController@update')->name('dashboard.users.update');
    Route::delete('/users/{user}', 'UserController@destroy')->name('dashboard.users.destroy');

    // Other resources can be added here

});

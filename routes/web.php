<?php

use App\Http\Controllers\SwaggerController;
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

Route::get('/', function () {
    return view('/welcome');
});

Route::post('/login', 'App\Http\Controllers\WebTestController@login')->name('login');
Route::post('/register', 'App\Http\Controllers\WebTestController@register')->name('register');

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

    Route::get('/dashboard', 'App\Http\Controllers\WebTestController@dashboard')->name('dashboard');
    Route::get('/logout', 'App\Http\Controllers\WebTestController@logout')->name('logout');


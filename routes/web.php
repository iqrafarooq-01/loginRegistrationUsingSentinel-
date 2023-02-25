<?php

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
    return view('welcome');
});

Route::get('/register' , 'App\Http\Controllers\Security\RegisterController@register');
Route::post('/register' , 'App\Http\Controllers\Security\RegisterController@registerUser');


Route::get('/login' , 'App\Http\Controllers\Security\LoginController@login');
Route::post('/login' , 'App\Http\Controllers\Security\LoginController@postlogin');


Route::get('/logout' , 'App\Http\Controllers\Security\LoginController@logout');

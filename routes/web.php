<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('home')->middleware('auth');

Route::get('/registro', function () {
    return view('registro');
})->name('usuario.registro')->middleware('guest');

Route::get('/login', function () {
    return view('login');
})->name('usuario.login')->middleware('guest');
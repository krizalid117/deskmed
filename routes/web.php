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

//Grupo con middleware "guest", para páginas antes sin sesión iniciada
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', function () {
        return view('registro');
    })->name('usuario.registro');

    Route::get('/login', function () {
        return view('login');
    })->name('usuario.login');
});

//Grupo con middleware "auth", para páginas que requieran sesión iniciada
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('index');
    })->name('home')->middleware('auth');
});
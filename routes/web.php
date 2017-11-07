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

Auth::routes();

//Grupo con middleware "guest", para páginas antes sin sesión iniciada
Route::group(['middleware' => 'guest'], function () {

    Route::get('/login', 'UsuarioController@login')->name('usuario.login');
    Route::post('/signin', 'UsuarioController@signIn')->name('usuario.signin');

    Route::get('/register', 'UsuarioController@register')->name('usuario.register');
    Route::post('/signup', 'UsuarioController@store')->name('usuario.signup');
});

//Grupo con middleware "auth", para páginas que requieran sesión iniciada
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/logout', 'UsuarioController@logout')->name('usuario.logout');

    //Rutas sólo para admins...
    Route::group(['middleware' => 'admins'], function () {
        Route::post('/test/users/create/{amaunt}', 'TestController@createUsers')->name('test.users.create');

        Route::get('/test/users/create/{amaunt}', 'TestController@createUsers')->name('test.users.create');

        Route::get('/admin/validations/', 'GlobalController@validations')->name('admin.validations');
    });

    //Rutas sólo para doctores...
    Route::group(['middleware' => 'doctors'], function () {
        Route::get('/user/career', 'UsuarioController@profesion')->name('usuario.profesion');

        Route::post('/user/career/verify', 'UsuarioController@sendVerification')->name('usuario.profesion.verify');

        Route::post('/user/career/savetemp', 'UsuarioController@guardarPPTemporal')->name('usuario.profesion.savetemp');

        Route::post('/sendaddlistrequest', 'UsuarioController@sendAddListRequest')->name('usuario.sendaddlistrequest');
    });

    //Rutas sólo para pacientes...
    Route::group(['middleware' => 'patients'], function () {
        Route::get('/user/record', 'UsuarioController@ficha')->name('usuario.ficha');
        Route::get('/patient/list', 'UsuarioController@listaDoctores')->name('paciente.doctores');

        Route::post('/user/familyrec/save_activation', 'UsuarioController@saveActivacionAntFam')->name('usuarios.ficha.saveActivacionAntFam');
        Route::post('/user/familyrec/save_especif', 'UsuarioController@saveEspecificacionAntFam')->name('usuarios.ficha.saveEspecificacionAntFam');
        Route::post('/user/add_edit_member', 'UsuarioController@addEditIntegrante')->name('usuarios.ficha.addEditIntegrante');
        Route::post('/user/remove_member', 'UsuarioController@removerIntegrante')->name('usuarios.ficha.removerIntegrante');
        Route::post('/user/cambio_condicion', 'UsuarioController@cambioCondicion')->name('usuarios.ficha.cambioCondicion');
        Route::post('/user/cambio_condicion_comentario', 'UsuarioController@cambioCondicionComentario')->name('usuarios.ficha.cambioCondicionComentario');

        Route::post('/user/add_doctor/{id}', 'UsuarioController@addDoctorToList')->name('patients.addDoctor');
    });

    Route::get('/patients/{id}/record/{notification_uuid?}', 'UsuarioController@patientProfile')->name('patients.profile');

    Route::get('/professionals/{id}/profile', 'UsuarioController@doctorProfile')->name('doctors.profile');

    Route::get('/user/profile', 'UsuarioController@profile')->name('usuario.profile');

    Route::post('/user/edit/{id}', 'UsuarioController@edit')->name('usuario.edit');

    Route::post('/user/uploadpic/{id}', 'UsuarioController@uploadPic')->name('usuario.uploadpic');

    Route::post('/user/deletepic/{id}', 'UsuarioController@deletePic')->name('usuario.deletepic');

    Route::get('/search/{keyword}', 'GlobalController@search')->name('search');

    Route::get('/getnotif', 'GlobalController@getNotifications')->name('usuario.getnotification');
});

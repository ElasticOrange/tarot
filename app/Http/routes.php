<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/home', function() {
    return redirect('/');
});

//Route::group(['middleware' => 'auth'], function(){
    Route::get('/sites/{sites}/delete', 'SiteController@destroy');
    Route::resource('sites', 'SiteController');

    Route::get('/sites/{sites}/infocosts/{infocosts}/delete', 'InfocostsController@destroy');
    Route::resource('sites.infocosts', 'InfocostsController');

    Route::get('/users/{users}/delete', 'UsersController@destroy');
    Route::resource('users', 'UsersController');

    Route::get('profile', 'UsersController@profile');
    Route::post('profile', 'UsersController@updateProfile');



    //Route::post('/templates/question', '');


    Route::get('/', function () {
        return view('questions');
    });

    Route::get('questions', function () {
        return view('questions');
    });

    Route::get('emails', function () {
        return view('emails');
    });

    Route::get('clients', function () {
        return view('clients');
    });

    Route::get('templates/question', function () {
        return view('question-templates');
    });

    Route::get('templates/question/item', function () {
        return view('question-template');
    });

    Route::get('templates/email', function () {
        return view('email-templates');
    });

    Route::get('templates/email/item', function () {
        return view('email-template');
    });

    Route::get('settings', function () {
        return view('settings');
    });


    Route::get('client', function () {
        return view('client');
    });



//});



Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


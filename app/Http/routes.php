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

Route::get('profile', function () {
    return view('profile');
});

Route::get('sites', function () {
    return view('sites');
});

Route::get('sites/item/{id?}', function () {
    return view('site');
});

Route::get('users', function () {
    return view('users');
});

Route::get('users/item/{id?}', function () {
    return view('user');
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

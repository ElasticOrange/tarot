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


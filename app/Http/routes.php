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

Route::group(['middleware' => 'auth'], function(){


    Route::post('/sites/{sites}/sendmail', 'EmailsController@sendMail');


    Route::post('/sites/change', 'SiteController@change');
    Route::get('/sites/{sites}/delete', 'SiteController@destroy');
    Route::resource('sites', 'SiteController');

    Route::get('/sites/{sites}/infocosts/{infocosts}/delete', 'InfocostsController@destroy');
    Route::resource('sites.infocosts', 'InfocostsController');

    Route::get('/users/{users}/delete', 'UsersController@destroy');
    Route::resource('users', 'UsersController');

    Route::get('profile', 'UsersController@profile');
    Route::post('profile', 'UsersController@updateProfile');


    Route::post('/sites/{sites}/templates/{templateCategory}', 'TemplatesController@changeSite')->where('templateCategory', '[A-Za-z]+');
    Route::get('/templates/{templateCategory}', 'TemplatesController@redirect');
    Route::get('/sites/{sites}/templates/{templateCategory}', 'TemplatesController@index')->where('templateCategory', '[A-Za-z]+');;
    Route::get('/sites/{sites}/templates/{templateCategory}/create', 'TemplatesController@create');
    Route::get('/sites/{sites}/templates/{templates}/get', 'TemplatesController@get');
    Route::resource('sites.templates', 'TemplatesController', ['except' => ['index', 'create']]);


    Route::get('/clients', 'ClientsController@redirect');
    Route::get('/sites/{sites}/clients/{clients}/{templateCategory}', 'ClientsController@show');
    Route::get('/sites/{sites}/clients/{clientEmailAddress}', 'ClientsController@editClientByEmail')->where('clientEmailAddress', '[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+@[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+');
    Route::get('/sites/{sites}/markresponded/{clientEmailAddress}', 'EmailsController@markEmailsFromAddressAsResponded')->where('clientEmailAddress', '[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+@[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+');;
    Route::get('/sites/{sites}/markresponded/{clients}', 'ClientsController@markClientEmailsAsResponded');
    Route::get('/sites/{sites}/markunresponded/{clientEmailAddress}', 'EmailsController@markEmailsFromAddressAsUnresponded')->where('clientEmailAddress', '[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+@[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+');;
    Route::get('/sites/{sites}/markunresponded/{clients}', 'ClientsController@markClientLastEmailAsUnresponded');
    Route::resource('sites.clients', 'ClientsController');


    Route::get('/sites/{sites}/emails', 'EmailsController@index');
    Route::get('/sites/{sites}/questions', 'EmailsController@unrespondedQuestions');
    Route::get('/sites/{sites}/nextquestion/{clients?}', 'EmailsController@nextQuestionForSite');
    Route::get('/sites/{sites}/nextemail/{clients?}', 'EmailsController@nextUnrespondedClientForSite');
    Route::get('/sites/{sites}/nextemailbytime/{timestamp?}', 'EmailsController@nextUnrespondedClientForSiteByTimestamp');
    Route::get('/sites/{sites}/emails/lastEmails/{clientEmailAddress}/{emailCount}', 'EmailsController@lastEmails')->where('clientEmailAddress', '[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+@[A-Za-z0-9\.\_\-\#\$\~\&\*\,\;\=\:]+');;

    Route::get('/emailboxes/{emailboxes}/delete', 'EmailboxController@destroy');
    Route::resource('emailboxes', 'EmailboxController');

    Route::get('/', function () {

        $sites = \Auth::user()->sites()->with(['emails' => function($query) {
            return $query->received()->unresponded();
        }])->with(['clients' => function($query) {
            return $query->confirmed()->withQuestionUnresponded();
        }])->get();

        return view('homepage', ['sites' => $sites]);
    });

    Route::get('questions', function () {
        $siteId = \Auth::user()->currentSiteId();
        return redirect("/sites/$siteId/questions");
    });

    Route::get('emails', function () {
        $siteId = \Auth::user()->currentSiteId();
        return redirect("/sites/$siteId/emails");
    });

    Route::get('settings', function () {
        return view('settings');
    });

    Route::post('/sites/{sites}/clients/{clients}/subscribe', 'ClientsController@subscribe');
    Route::get('client', function () {
        return view('client');
    });



});



Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


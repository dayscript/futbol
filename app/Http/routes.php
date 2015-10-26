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


Route::get('/','PagesController@dashboard');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('dashboard','PagesController@dashboard');
Route::get('help','PagesController@help');

Route::resource('optafeeds','OptafeedsController');
Route::resource('optagames','OptagamesController');
Route::get('optagames/{optagames}/{option}', 'OptagamesController@show');
Route::resource('users','UsersController');
Route::post('fixtureteams/{id}', 'FixturesController@updateTeam');
Route::resource('fixtures','FixturesController');
Route::get('fixtures/{fixtures}/{option}', 'FixturesController@show');
Route::resource('tournaments','TournamentsController');
Route::get('tournaments/sync/{optaid}/{optaseason}', 'TournamentsController@sync');
Route::get('tournaments/updatewidget/{tournaments}', 'TournamentsController@updatewidget');
Route::get('tournaments/{tournaments}/{option}', 'TournamentsController@show');
Route::get('optafeeds/{optafeeds}/process', 'OptafeedsController@process');
Route::resource('teams','TeamsController');

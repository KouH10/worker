<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
//Route::get('/home', 'HomeController@index');
*/

Route::get('/', 'WorkController@index');
Route::get('/home', 'HomeController@index');
Route::resource('work', 'WorkController');
Route::resource('workconfirmation', 'Work\ConfirmationController');
Route::resource('workregister', 'Work\RegisterController');

Route::post('workvacations/store','WorkVacationsController@store');
Route::resource('workvacations','WorkVacationsController');

Route::post('groups/store','Groups\GroupsController@store');
Route::get('groups/edit/{id}','Groups\GroupsController@edit');
Route::post('groups/update/{id}','Groups\GroupsController@update');

Route::resource('groups', 'Groups\GroupsController');
Route::post('groupholidays/store','GroupHolidaysController@store');
Route::resource('groupholidays', 'GroupHolidaysController');

Route::post('affiliations/store','AffiliationsController@store');
Route::get('affiliations/edit/{id}','AffiliationsController@edit');
Route::resource('affiliations', 'AffiliationsController');
Auth::routes();

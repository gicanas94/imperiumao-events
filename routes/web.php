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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('', 'IndexController@index')->name('index');

Route::get('users', 'UsersController@index')->name('users')->middleware(['power1']);
Route::post('users', 'UsersController@store')->middleware(['power3']);
Route::post('users/destroy/{id}', ['as' => 'users.destroy', 'uses' => 'UsersController@destroy'])->middleware(['power3', 'adminAccount']);
Route::post('users/state/{id}', ['as' => 'users.state', 'uses' => 'UsersController@state'])->middleware(['power3', 'adminAccount']);
Route::get('users/records/{id}', ['as' => 'users.records', 'uses' => 'UsersController@records'])->middleware(['power1', 'adminAccount']);
Route::post('users/records/{id}', ['as' => 'users.records', 'uses' => 'UsersController@records'])->middleware(['power1', 'adminAccount']);
Route::get('users/edit/{id}', ['as' => 'users.edit', 'uses' => 'UsersController@edit'])->middleware(['power3', 'adminAccount']);
Route::post('users/update/{id}', ['as' => 'users.update', 'uses' => 'UsersController@update'])->middleware(['power3', 'adminAccount']);

Route::get('events', 'EventsController@index')->name('events')->middleware(['power2']);
Route::post('events', 'EventsController@store')->middleware(['power2']);
Route::post('events/destroy/{id}', ['as' => 'events.destroy', 'uses' => 'EventsController@destroy'])->middleware(['power2']);
Route::post('events/state/{id}', ['as' => 'events.state', 'uses' => 'EventsController@state'])->middleware(['power2']);
Route::get('events/edit/{id}', ['as' => 'events.edit', 'uses' => 'EventsController@edit'])->middleware(['power2']);
Route::post('events/update/{id}', ['as' => 'events.update', 'uses' => 'EventsController@update'])->middleware(['power2']);

Route::get('startevent/{id}', 'StartEventController@index')->name('startevent')->middleware(['eventIsDeactivated']);
Route::post('startevent/start/{id}', ['as' => 'startEvent.start', 'uses' => 'StartEventController@start'])->middleware(['eventIsDeactivated']);

Route::get('finishevent/{id}', 'FinishEventController@index')->name('finishevent')->middleware(['notEventOwner', 'eventIsFinished']);
Route::post('finishevent/finish/{id}', ['as' => 'finishEvent.finish', 'uses' => 'FinishEventController@finish'])->middleware(['notEventOwner', 'eventIsFinished']);

Route::get('progress/{id}', 'InProgressController@index')->name('progress')->middleware(['eventIsFinished']);

Route::get('account', 'AccountController@index')->name('account');
Route::post('account/update', ['as' => 'account.update', 'uses' => 'AccountController@update']);

Route::get('messages', 'MessagesController@index')->name('messages')->middleware(['power3']);
Route::post('messages', 'MessagesController@store')->middleware(['power3']);
Route::post('messages/destroy/{id}', ['as' => 'messages.destroy', 'uses' => 'MessagesController@destroy'])->middleware(['power3']);
Route::post('messages/state/{id}', ['as' => 'messages.state', 'uses' => 'MessagesController@state'])->middleware(['power3']);
Route::get('messages/edit/{id}', ['as' => 'messages.edit', 'uses' => 'MessagesController@edit'])->middleware(['power3']);
Route::post('messages/update/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update'])->middleware(['power3']);

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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes(['register' => false, 'reset' => false]);

// Route::get('/elections', 'ElectionController@index')->name('elections.home');
Route::resource('elections', 'ElectionController')->middleware('auth');
Route::resource('vote', 'VoteController')->middleware('auth');
Route::get('privacy', 'HomeController@privacy');


Route::middleware(['admin'])->prefix('admin')->group(function () {
  Route::get('/', 'AdminController@index')->name('admin.dashboard');
  Route::resource('meta', 'MetaController');
  Route::resource('elections', 'ElectionController');
  Route::post('users/storeAdmin', 'UserController@storeAdmin')->name('users.storeAdmin');
  Route::resource('users', 'UserController');
  Route::get('export-users', 'UserController@export');
  Route::resource('candidates', 'CandidateController');
  Route::get('export-results/{election}', 'VoteController@export');

});

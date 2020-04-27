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

Route::get('/', function () {
    return redirect('/admin');
});
Route::view('/bulksms', 'bulksms');
Route::post('/bulksms', 'BulkSmsController@sendSms');
Route::get('/sendmail','mailController@index');
Route::post('/send','mailController@send');
Route::get('/craw','craw@index');
Route::get('test', function () {
    testJob::dispatch('TESTER');
});



// ================================
// VOYAGER
//==================================
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

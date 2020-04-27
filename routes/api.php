<?php

use Illuminate\Http\Request;
use App\Http\Resources\LoginResource;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Stay 

Route::group(['prefix' => 'stay'], function () {
    Route::get('/search','api\stayController@search');

    Route::get('/get-highlight-places','api\stayController@stayGetHighlightPlaces');
    
    Route::get('/get-slices-by-type','api\stayController@getSlicesByType');
    
    Route::get('/get-stay-hot','api\stayController@getHotStay');
    
    Route::get('/{stay_id}/detail','api\stayController@getStayDetail');
    
    Route::get('/{stay_id}/comments','api\stayController@getStayComments');
    
    Route::post('/comments','api\stayController@postStayComment');

    //favorite
    Route::post('/add-favorite','api\stayController@addFavorite');

    Route::delete('/{favorite_id}/remove-favorite','api\stayController@removeFavorite');

    Route::get('/{user_id}/get-favorite','api\stayController@getFavorite');

});

//booking
Route::group(['prefix' => 'booking'], function () {
    Route::get('/{booking_id}/get', 'api\BookingController@getbooking');

    Route::post('/add', 'api\BookingController@addBooking');
    
    Route::put('/update','api\BookingController@updateBooking');
    
    Route::get('/{user_id}/all-booked-list','api\BookingController@getAllBookedList');
    Route::get('/{user_id}/pending-list','api\BookingController@getIncompleteList');
    Route::get('/{user_id}/completed-list','api\BookingController@getCompletedList');
    Route::delete('/{user_id}/cancel-booking','api\BookingController@cancelBookingById');
    

});

//host info
Route::get('host/{host_id}/info', 'api\userController@getHostInfo');


//stripe
Route::group(['prefix' => 'stripe'], function () {

Route::post('/payment-intent', 'api\StripeController@paymentIntent');

Route::post('/confirm-payment', 'api\StripeController@confirmPayment');
});

//user
Route::group(['prefix' => 'user'], function () {

Route::post('/signin','api\userController@signIn');
Route::post('/signup','api\userController@signUp');
Route::post('/update','api\userController@update');
Route::post('/reset-password','api\userController@resetPassword');
Route::post('/change-password','api\userController@changepass');
});

Route::middleware('jwt.verify')->get('user/info','api\userController@infoUser' );








Route::get('province','api\provinceController@index');



Route::get('download','HomeController@download');

Route::post('test','api\BookingController@sendInvoid');
Route::get('fail','api\BookingController@get_fail');







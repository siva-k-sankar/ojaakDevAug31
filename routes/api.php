<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('social', 'API\UserController@social');
Route::post('sendotp', 'API\UserController@sendotp');

Route::post('forgot-password', 'API\UserController@forgot_password');

Route::group(['middleware' => 'auth:api'], function(){

	//Users
	Route::get('userinfo', 'API\UserController@userinfo');
	Route::post('logout','API\UserController@logoutApi');
	Route::post('verify', 'API\UserController@verifyotp');
	Route::post('sendverifymail', 'API\ProfileController@verifymail');
	Route::get('home', 'API\HomeController@home');
	Route::get('item/{auuid}', 'API\HomeController@aditem');
    Route::post('items','API\HomeController@getads');
    Route::get('my_active_ads','API\AdsController@myactiveads');
    Route::get('my_sold_ads','API\AdsController@mysoldads');
    Route::get('my_fav_ads','API\AdsController@myfavads');
    Route::get('my_inactive_ads','API\AdsController@myinactiveads');

    Route::post('favads','API\AdsController@favouriteads');
    Route::post('search','API\AdsController@search');

    //Route::get('categories','API\HomeController@categories');
    Route::get('categories/{cuuid?}','API\HomeController@categories');
    //Route::get('sub_categories/{cuuid?}','API\HomeController@sub_categories');

    Route::get('getattributes/{subCateuuid?}','API\AdsController@getextraattributes');
    Route::post('ad_post','API\AdsController@ad_post');
    Route::get('get_location','API\AdsController@get_location');



    Route::post('change-password', 'API\UserController@change_password');
    Route::post('redeemamount', 'API\UserController@redeemamount');
    Route::post('walletpassbook', 'API\UserController@walletpassbook');
    Route::post('profileupdate', 'API\UserController@profileupdate');
	

});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/user')->group( function() {
    Route::post('/login', 'api\LoginController@login');
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::apiResource('/user', 'AdduserinfoController');
    Route::get('/api/user/{id}', 'AdduserinfoController@showbyid');
    
    //Register Section
//  {

        //Arms Register
        Route::apiResource('/arms', 'ArmsregisterController');
        Route::get('/arms/showbyppid/{ppid}', 'ArmsregisterController@showbyppid');
        Route::get('/arms/showbypsid/{psid}', 'ArmsregisterController@showbypsid');

        //Seize Register
        Route::apiResource('/seize', 'SeizeregisterController');
        Route::get('/seize/showbyppid/{ppid}', 'SeizeregisterController@showbyppid');
        Route::get('/seize/showbypsid/{psid}', 'SeizeregisterController@showbypsid');

        // Movement Register
        Route::apiResource('/movement', 'MovementregisterController');
        Route::get('/movement/showbyppid/{ppid}', 'MovementregisterController@showbyppid');
        Route::get('/movement/showbypsid/{psid}', 'MovementregisterController@showbypsid');

        
        //Watch Register
        Route::apiResource('/watch', 'WatchregisterController');
        Route::get('/watch/showbyppid/{ppid}', 'WatchregisterController@showbyppid');
        Route::get('/watch/showbypsid/{psid}', 'WatchregisterController@showbypsid');

        //Crime Register
        Route::apiResource('/crime', 'CrimeregisterController');
        Route::get('/crime/showbyppid/{ppid}', 'CrimeregisterController@showbyppid');
        Route::get('/crime/showbypsid/{psid}', 'CrimeregisterController@showbypsid');

        //Fire Register
        Route::apiResource('/fire', 'FireregisterController');
        Route::get('/fire/showbyppid/{ppid}', 'FireregisterController@showbyppid');
        Route::get('/fire/showbypsid/{psid}', 'FireregisterController@showbypsid');

        Route::apiResource('/useraccess', 'UseraccessController');
        Route::post('/useraccesstable/{user_id}', 'UseraccessController@useraccesstable');
    

//  }

});
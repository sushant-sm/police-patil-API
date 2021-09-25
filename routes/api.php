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

    Route::apiResource('/arms', 'ArmsregisterController');
    Route::get('/arms/showbyppid/{ppid}', 'ArmsregisterController@showbyppid');
    Route::get('/arms/showbypsid/{psid}', 'ArmsregisterController@showbypsid');

    Route::apiResource('/seize', 'SeizeregisterController');
    Route::get('/seize/showbyppid/{ppid}', 'SeizeregisterController@showbyppid');
    Route::get('/seize/showbypsid/{psid}', 'SeizeregisterController@showbypsid');

    Route::apiResource('/movement', 'MovementregisterController');
    Route::get('/movement/showbyppid/{ppid}', 'MovementregisterController@showbyppid');
    Route::get('/movement/showbypsid/{psid}', 'MovementregisterController@showbypsid');

    
});
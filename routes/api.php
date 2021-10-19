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

Route::prefix('/user')->group(function () {
    Route::post('/login', 'api\LoginController@login');
});
Route::post('/admin/login', 'AdminController@login');
Route::apiResource('/admin', 'AdminController');
Route::post('adduser', 'AuthController@register');
Route::group(['middleware' => ['auth:api']], function () {

    // Route::put('/user/{id}', 'AdduserinfoController@update');
    Route::apiResource('/user', 'AdduserinfoController');
    Route::get('/api/user/{id}', 'AdduserinfoController@showbyid');

    Route::apiResource('/useraccess', 'UseraccessController');
    Route::post('/useraccesstable/{user_id}', 'UseraccessController@useraccesstable');

    Route::apiResource('/policestation', 'PolicestationController');

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
    Route::get('/crimecount', 'CrimeregisterController@crimecount');
    Route::get('/crime/showbyppid/{ppid}', 'CrimeregisterController@showbyppid');
    Route::get('/crime/showbypsid/{psid}', 'CrimeregisterController@showbypsid');

    //Fire Register
    Route::apiResource('/fire', 'FireregisterController');
    Route::get('/fire/showbyppid/{ppid}', 'FireregisterController@showbyppid');
    Route::get('/fire/showbypsid/{psid}', 'FireregisterController@showbypsid');

    //Death Register
    Route::apiResource('/death', 'DeathregisterController');
    Route::get('/death/showbyppid/{ppid}', 'DeathregisterController@showbyppid');
    Route::get('/death/showbypsid/{psid}', 'DeathregisterController@showbypsid');

    //Missing Register
    Route::apiResource('/missing', 'MissingregisterController');
    Route::get('/missing/showbyppid/{ppid}', 'MissingregisterController@showbyppid');
    Route::get('/missing/showbypsid/{psid}', 'MissingregisterController@showbypsid');

    //Public place Register
    Route::apiResource('/publicplace', 'PublicplaceregisterController');
    Route::get('/publicplace/showbyppid/{ppid}', 'PublicplaceregisterController@showbyppid');
    Route::get('/publicplace/showbypsid/{psid}', 'PublicplaceregisterController@showbypsid');

    //Illegal work Register
    Route::apiResource('/illegalwork', 'IllegalworkregisterController');
    Route::get('/illegalwork/showbyppid/{ppid}', 'IllegalworkregisterController@showbyppid');
    Route::get('/illegalwork/showbypsid/{psid}', 'IllegalworkregisterController@showbypsid');

    //  }


    Route::apiResource('/news', 'NewsController');
    Route::apiResource('/alert', 'AlertController');

    Route::apiResource('/kayade', 'KayadeController');

    Route::apiResource('/disaster', 'DisasterController');
    Route::get('/disaster/showbyppid/{ppid}', 'DisasterController@showbyppid');
    Route::get('/disaster/showbypsid/{psid}', 'DisasterController@showbypsid');

    Route::apiResource('/disastertools', 'DisastertoolsController');
    Route::get('/disastertools/showbyppid/{ppid}', 'DisastertoolsController@showbyppid');
    Route::get('/disastertools/showbypsid/{psid}', 'DisastertoolsController@showbypsid');

    Route::apiResource('/disasterhelper', 'DisasterhelperController');
    Route::get('/disasterhelper/showbyppid/{ppid}', 'DisasterhelperController@showbyppid');
    Route::get('/disasterhelper/showbypsid/{psid}', 'DisasterhelperController@showbypsid');

    Route::apiResource('/alltables', 'AlltableController');
    Route::apiResource('/useraccess', 'UseraccessController');
});

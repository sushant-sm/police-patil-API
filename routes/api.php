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

Route::get('/baseurl', 'BaseurlController@index');
Route::post('adduser', 'AuthController@register');
Route::group(['middleware' => ['auth:api']], function () {

    Route::apiResource('/user', 'AdduserinfoController');
    Route::get('/village', 'AdduserinfoController@village');
    Route::get('/pp', 'AdduserinfoController@getallpp');
    Route::get('/ps', 'AdduserinfoController@getallps');
    Route::get('/dangerzone', 'AdduserinfoController@dangerzone');
    Route::get('/version', 'VersionController@index');

    Route::apiResource('/policestation', 'PolicestationController');


    //Register Section
    //  {

    //Arms Register
    Route::put('/arms/edit', 'ArmsregisterController@update');
    Route::delete('/arms/delete', 'ArmsregisterController@destroy');
    Route::apiResource('/arms', 'ArmsregisterController');
    Route::get('/arms/showbyppid/{ppid}', 'ArmsregisterController@showbyppid');
    Route::get('/arms/showbypsid/{psid}', 'ArmsregisterController@showbypsid');

    //Seize Register
    Route::put('/seize/edit', 'SeizeregisterController@update');
    Route::delete('/seize/delete', 'SeizeregisterController@destroy');
    Route::apiResource('/seize', 'SeizeregisterController');
    Route::get('/seize/showbyppid/{ppid}', 'SeizeregisterController@showbyppid');
    Route::get('/seize/showbypsid/{psid}', 'SeizeregisterController@showbypsid');

    // Movement Register
    Route::put('/movement/edit', 'MovementregisterController@update');
    Route::delete('/movement/delete', 'MovementregisterController@destroy');
    Route::apiResource('/movement', 'MovementregisterController');
    Route::get('/latestmovement', 'MovementregisterController@latest');
    Route::get('/movement/showbyppid/{ppid}', 'MovementregisterController@showbyppid');
    Route::get('/movement/showbypsid/{psid}', 'MovementregisterController@showbypsid');


    //Watch Register
    Route::put('/watch/edit', 'WatchregisterController@update');
    Route::delete('/watch/delete', 'WatchregisterController@destroy');
    Route::apiResource('/watch', 'WatchregisterController');
    Route::get('/latestwatch', 'WatchregisterController@latest');
    Route::get('/watch/showbyppid/{ppid}', 'WatchregisterController@showbyppid');
    Route::get('/watch/showbypsid/{psid}', 'WatchregisterController@showbypsid');

    //Crime Register
    Route::put('/crime/edit', 'CrimeregisterController@update');
    Route::delete('/crime/delete', 'CrimeregisterController@destroy');
    Route::apiResource('/crime', 'CrimeregisterController');
    Route::get('/crimecount', 'CrimeregisterController@crimecount');
    Route::get('/crime/showbyppid/{ppid}', 'CrimeregisterController@showbyppid');
    Route::get('/crime/showbypsid/{psid}', 'CrimeregisterController@showbypsid');

    //Fire Register
    Route::put('/fire/edit', 'FireregisterController@update');
    Route::delete('/fire/delete', 'FireregisterController@destroy');
    Route::apiResource('/fire', 'FireregisterController');
    Route::get('/fire/showbyppid/{ppid}', 'FireregisterController@showbyppid');
    Route::get('/fire/showbypsid/{psid}', 'FireregisterController@showbypsid');

    //Death Register
    Route::put('/death/edit', 'DeathregisterController@update');
    Route::delete('/death/delete', 'DeathregisterController@destroy');
    Route::apiResource('/death', 'DeathregisterController');
    Route::get('/death/showbyppid/{ppid}', 'DeathregisterController@showbyppid');
    Route::get('/death/showbypsid/{psid}', 'DeathregisterController@showbypsid');

    //Missing Register
    Route::put('/missing/edit', 'MissingregisterController@update');
    Route::delete('/missing/delete', 'MissingregisterController@destroy');
    Route::apiResource('/missing', 'MissingregisterController');
    Route::get('/missing/showbyppid/{ppid}', 'MissingregisterController@showbyppid');
    Route::get('/missing/showbypsid/{psid}', 'MissingregisterController@showbypsid');

    //Public place Register
    Route::put('/publicplace/edit', 'PublicplaceregisterController@update');
    Route::delete('/publicplace/delete', 'PublicplaceregisterController@destroy');
    Route::apiResource('/publicplace', 'PublicplaceregisterController');
    Route::get('/publicplace/showbyppid/{ppid}', 'PublicplaceregisterController@showbyppid');
    Route::get('/publicplace/showbypsid/{psid}', 'PublicplaceregisterController@showbypsid');

    //Illegal work Register
    Route::put('/latestillegalwork/edit', 'IllegalworkregisterController@update');
    Route::delete('/latestillegalwork/delete', 'IllegalworkregisterController@destroy');
    Route::apiResource('/illegalwork', 'IllegalworkregisterController');
    Route::get('/latestillegalwork', 'IllegalworkregisterController@latest');
    Route::get('/illegalwork/showbyppid/{ppid}', 'IllegalworkregisterController@showbyppid');
    Route::get('/illegalwork/showbypsid/{psid}', 'IllegalworkregisterController@showbypsid');

    Route::apiResource('/gramsuraksha', 'GramsurakshaController');
    //  }

    Route::get('topnews', 'NewsController@topnews');
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

    Route::post('/mandhan', 'MandhanController@index');
    Route::post('/certificate', 'CertificateController@index');

    Route::get('/top-pp', 'PointsController@index');
    // Route::get('/points')
});

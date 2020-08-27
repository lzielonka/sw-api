<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'characters'], function () {
    Route::get('/', 'Api\CharacterController@index');
    Route::post('/', 'Api\CharacterController@store');
    Route::get('/{character}', 'Api\CharacterController@show');
    Route::put('/{character}', 'Api\CharacterController@update');
    Route::delete('/{character}', 'Api\CharacterController@destroy');
    Route::post('/{character}/friends/{friend}', 'Api\CharacterController@addFriend');
    Route::delete('/{character}/friends/{friend}', 'Api\CharacterController@removeFriend');
});

Route::group(['prefix' => 'episodes'], function () {
    Route::get('/', 'Api\EpisodeController@index');
    Route::post('/', 'Api\EpisodeController@store');
    Route::get('/{episode}', 'Api\EpisodeController@show');
    Route::put('/{episode}', 'Api\EpisodeController@update');
    Route::delete('/{episode}', 'Api\EpisodeController@destroy');
    Route::post('/{episode}/characters/{character}', 'Api\EpisodeController@addCharacter');
    Route::delete('/{episode}/characters/{character}', 'Api\EpisodeController@removeCharacter');
});

Route::group(['prefix' => 'planets'], function () {
    Route::get('/', 'Api\PlanetController@index');
    Route::post('/', 'Api\PlanetController@store');
    Route::get('/{planet}', 'Api\PlanetController@show');
    Route::put('/{planet}', 'Api\PlanetController@update');
    Route::delete('/{planet}', 'Api\PlanetController@destroy');
});


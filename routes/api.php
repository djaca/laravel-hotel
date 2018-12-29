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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('rooms', 'Api\RoomsController@index');
Route::post('rooms', 'Api\RoomsController@store');
Route::patch('rooms/{room}', 'Api\RoomsController@update');
Route::delete('rooms/{room}', 'Api\RoomsController@destroy');

Route::get('room-types', 'Api\RoomTypesController@index');
Route::post('room-types', 'Api\RoomTypesController@store');

Route::get('guests', 'Api\GuestsController@index');
Route::post('guests', 'Api\GuestsController@store');
Route::get('guests/{guest}', 'Api\GuestsController@show');

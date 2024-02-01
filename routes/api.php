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
Route::post('authenticate', 'App\Http\Controllers\AuthController@login');
Route::group(['middleware' => ['auth:api']], function () {
	Route::post('logout', 'App\Http\Controllers\AuthController@logout');
	Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
	Route::get('me', 'App\Http\Controllers\AuthController@me');

	Route::get('get_game', 'App\Http\Controllers\GameController@get_game');
	Route::get('get_score', 'App\Http\Controllers\GameController@show');
	Route::post('save_score', 'App\Http\Controllers\GameController@store');
	Route::get('exporttopdf','App\Http\Controllers\GameController@export_to_pdf');
	Route::get('exporttoexcel','App\Http\Controllers\GameController@export_to_excel');
});
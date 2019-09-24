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

Route::post('/add_emp',['as'=>'add_emp','uses'=>'EmployeeController@add_emp']);
Route::post('/cal_slry',['as'=>'cal_slry','uses'=>'EmployeeController@monthly_data']);

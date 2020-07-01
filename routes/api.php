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

Route::get("todolist/read/{id}", "ApiTodoListController@getRead");
Route::post("todolist/delete/{id}", "ApiTodoListController@postDelete");
Route::post("todolist/update/{id}", "ApiTodoListController@postUpdate");
Route::post("todolist/create", "ApiTodoListController@postCreate");
Route::get("todolist/list", "ApiTodoListController@getList");

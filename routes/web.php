<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/api/tracks", "TrackController@index");
Route::get("/api/tracks/search/", "TrackController@search");
Route::get("/api/tracks/{track}", "TrackController@show");

Route::get("/api/users", "UserAPIController@index");
Route::get("/api/users/{username}", "UserAPIController@show");

Route::post("/api/users", "UserAPIController@store");

Route::middleware("jwt.auth")->group(function(){
    Route::post("/api/tracks", "TrackController@store");
    Route::post("/api/tracks/{track}/edit", "TrackController@update");
    Route::delete("/api/tracks/{track}", "TrackController@destroy");

    Route::post("/api/users/{username}/edit", "UserAPIController@update");
});

Route::post("/api/token", "AuthenticateController@authenticate");

Route::get("/", function(){
    return view("app");
});
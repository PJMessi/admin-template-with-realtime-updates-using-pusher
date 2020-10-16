<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/logout", [AuthController::class, "logout"])->middleware("auth:api");
});

Route::group(["prefix" => "users", "middleware" => "auth:api"], function() {
    Route::post("/", [UserController::class, "store"]);
    Route::get("/", [UserController::class, "index"]);
    Route::get("/{user_id}", [UserController::class, "show"]);
    Route::put("/{user_id}", [UserController::class, "update"]);
    Route::delete("/{user_id}", [UserController::class, "destroy"]);
});

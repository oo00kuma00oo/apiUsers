<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::prefix("v1")->group(function(){
    Route::get("users/{id}",[UserController::class,'show'])->name("users.show");
    Route::post("users",[UserController::class,'store'])->name("users.store");
    Route::patch("users/{id}",[UserController::class,'update'])->name("users.update");
    Route::delete("users/{id}",[UserController::class,'destory'])->name("users.destory");
});

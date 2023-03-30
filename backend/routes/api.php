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


Route::get('/auth/twitter', [AuthController::class,'redirectToTwitter'])->name('twitter.auth');
Route::get('/auth/twitter/callback', [AuthController::class,'handleTwitterCallback'])->name('twitter.callback');

Route::middleware('auth:api')->group(function () {
    // Your protected routes here
});
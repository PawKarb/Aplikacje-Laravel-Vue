<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout']);
Route::apiResource('user','UserController')->only([
    'update', 'destroy'
]);
Route::post('/user/updatepassword', [UserController::class,'updatePassword']);
Route::post('/notify/{user}', [MainController::class,'sendNotification']);
Route::post('/reset/password', [PasswordResetController::class,'callResetPassword']);
Route::post('/reset-password', [LoginController::class,'sendPasswordLink']);

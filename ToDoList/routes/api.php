<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoListController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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
Route::get('/list', [ToDoListController::class, 'getlist'])->middleware('auth:sanctum');

Route::get('/login', [LoginController::class,'index'])->name('login');

Route::get('/register', [RegisterController::class,'index']);

Route::post('/register/send', [RegisterController::class,'sendRegister']);

Route::post('/login/send', [LoginController::class,'sendLogin']);

Route::post('/logout', [LoginController::class,'logout'])->middleware('auth:sanctum');

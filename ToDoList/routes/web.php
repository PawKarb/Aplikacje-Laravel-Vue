<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToDoListController;
use App\Http\Controllers\AuthorController;

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

Route::get('/', [HomeController::class,'index']);

Route::get('/list', [ToDoListController::class, 'list']);

Route::get('/author', [AuthorController::class,'show']);

Route::post('/savelist',[ToDoListController::class, 'saveList'])->middleware('auth:sanctum');

Route::delete('/delete/{id}',[ToDoListController::class, 'destroyList'])->middleware('auth:sanctum');

Route::post('/list/makedone/{id}',[ToDoListController::class, 'makeDone'])->middleware('auth:sanctum');

Route::post('/list/edit/{id}',[ToDoListController::class, 'editList'])->middleware('auth:sanctum');



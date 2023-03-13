<?php

use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/users', [UserController::class, 'index']);
Route::get('/user/new', [UserController::class, 'create']);
Route::get('/user/delete/{id}', [UserController::class, 'confirm']);
Route::get('/user/edit/{id}', [UserController::class, 'edit']);

Route::post('/user', [UserController::class, 'store']);
Route::post('/user/delete/{id}', [UserController::class, 'delete']);
Route::post('/user/update/{id}', [UserController::class, 'update']);

Route::post('/user/{id}/photo/upload', [PhotoController::class, 'upload']);
Route::get('/user/{id}/photo/remove', [PhotoController::class, 'remove']);

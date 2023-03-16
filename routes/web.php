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


Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/new', [UserController::class, 'create']);
    Route::get('/delete/{id}', [UserController::class, 'confirm']);
    Route::get('/edit/{id}', [UserController::class, 'edit']);

    Route::post('/', [UserController::class, 'store']);
    Route::post('/delete/{id}', [UserController::class, 'delete']);
    Route::post('/update/{id}', [UserController::class, 'update']);

    Route::post('/{id}/photo/upload', [PhotoController::class, 'upload']);
    Route::get('/{id}/photo/remove', [PhotoController::class, 'remove']);
});

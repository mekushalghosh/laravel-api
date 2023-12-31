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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/allusers',[UserController::class, 'getUsers']);

Route::post('/createuser',[UserController::class, 'createUser']);

Route::post('/updateuser',[UserController::class, 'updateUser']);

Route::post('/deleteuser',[UserController::class, 'deleteUser']);

Route::post('/login',[UserController::class, 'loginUser']);

Route::get('/myaccount', [UserController::class, 'userDetails'])->middleware('auth:sanctum');
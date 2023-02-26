<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminsController;

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

// Route::post('/categories',[CategoryController::class,'store']);
// Route::post('/categories',[CategoryController::class,'store']);
// Route::post('/categories',[CategoryController::class,'store']);
Route::Get('/admins',[adminsController::class,'Getadmins']);
Route::Post('/admins',[adminsController::class,'Postadmins']);
Route::Patch('/admins/{id}',[adminsController::class,'Editadmins']);
Route::Delete('/admins/{id}',[adminsController::class,'Deleteadmins']);

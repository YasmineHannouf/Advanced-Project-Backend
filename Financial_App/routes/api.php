<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/categories',[CategoryController::class,'store']);




//Incomes routes

Route::get('/incomes/show',[IncomeController::class,'getIncomes']);
Route::get('/incomes/show/sortByDateAsc',[IncomeController::class,'sortByDateAsc']);
Route::get('/incomes/show/sortByDateDesc',[IncomeController::class,'sortByDateDesc']);
Route::get('/incomes/show/sortByAmountDesc',[IncomeController::class,'sortByAmountDesc']);
Route::get('/incomes/show/sortByTitleDesc',[IncomeController::class,'sortByTitleDesc']);

Route::post('/incomes/store',[IncomeController::class,'addIncome']);
Route::patch('/incomes/update/{id}',[IncomeController::class,'updateIncome']);
Route::delete('/incomes/delete/{id}',[IncomeController::class,'deleteIncome']);
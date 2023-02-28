<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminsController;
use App\Http\Controllers\ProfitGoalsController;



Route::Get('/admins',[adminsController::class,'Getadmins']);
Route::Post('/admins',[adminsController::class,'Postadmins']);
Route::Patch('/admins/{id}',[adminsController::class,'Editadmins']);
Route::Delete('/admins/{id}',[adminsController::class,'Deleteadmins']);




// expenses
Route::get('/expenses', [ExpenseController::class, 'getExpenses']);
Route::get('/expenses/{id}' ,[ExpenseController::class, 'getExpensesById']);
Route::post('/expenses', [ExpenseController::class, 'addExpenses']);
Route::delete('/expenses/{id}', [ExpenseController::class, 'deleteExpenses']);
Route::put('/expenses/{id}', [ExpenseController::class, 'editExpenses']);


Route::post('/categories',[CategoryController::class,'store']);
Route::get('/categories',[CategoryController::class,'show']);
Route::patch('/categories/edit/{id}',[CategoryController::class,'edit']);
Route::delete('/categories/delete/{id}',[CategoryController::class,'delete']);
Route::get('/categories/sortByNameDesc',[CategoryController::class,'sortByNameDesc']);
Route::get('/categories/sortByNameAsc',[CategoryController::class,'sortByNameAsc']);



//Incomes routes

Route::get('/incomes/show',[IncomeController::class,'getIncomes']);
Route::get('/incomes/show/sortByDateAsc',[IncomeController::class,'sortByDateAsc']);
Route::get('/incomes/show/sortByDateDesc',[IncomeController::class,'sortByDateDesc']);
Route::get('/incomes/show/sortByAmountDesc',[IncomeController::class,'sortByAmountDesc']);
Route::get('/incomes/show/sortByTitleDesc',[IncomeController::class,'sortByTitleDesc']);

Route::post('/incomes/store',[IncomeController::class,'addIncome']);
Route::patch('/incomes/update/{id}',[IncomeController::class,'updateIncome']);
Route::delete('/incomes/delete/{id}',[IncomeController::class,'deleteIncome']);


Route::Get('/profit_goals',[ProfitGoalsController::class,'Get_Profit_Goals']);
Route::Post('/profit_goals',[ProfitGoalsController::class,'Post_Profit_Goals']);
Route::Delete('/profit_goals/{id}',[ProfitGoalsController::class,'Delete_Profit_Goals']);
Route::patch('/profit_goals/{id}',[ProfitGoalsController::class,'Edit_Profit_Goals']);

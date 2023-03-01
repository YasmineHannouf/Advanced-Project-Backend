<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReccuringController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminsController;



Route::Get('/admins',[adminsController::class,'Getadmins']);
Route::Post('/admins',[adminsController::class,'Postadmins']);
Route::Put('/admins/{id}',[adminsController::class,'Editadmins']);//need fixed
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



//Reccuring routes

Route::get('/reccurings/show',[ReccuringController::class,'show']);

Route::post('/reccurings/store',[ReccuringController::class,'store']);
Route::patch('/reccurings/update/{id}',[ReccuringController::class,'edit']);
Route::delete('/reccurings/delete/{id}',[ReccuringController::class,'delete']);


Route::get('/fixed/filter/{filter}/{value}', [FixedController::class, 'filter']);




Route::post('/login',[adminsController::class,'login']);

Route::middleware('auth')->group(function () {

    Route::get('/logout',[adminsController::class,'logout']);

    Route::get("/get",function(){
        return 'AUTH ROUTE';
        });
});

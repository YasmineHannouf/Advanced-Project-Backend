<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReccuringController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminsController;
use App\Http\Controllers\ProfitGoalsController;
use App\Http\Controllers\FixedController;
use App\Http\Controllers\Fixedkey;


Route::Get('/admins',[adminsController::class,'Getadmins']);
Route::Post('/admins',[adminsController::class,'Postadmins']);
Route::Put('/admins/{id}',[adminsController::class,'Editadmins']);//need fixed
Route::Delete('/admins/{id}',[adminsController::class,'Deleteadmins']);



Route::post('/categories',[CategoryController::class,'store']);
Route::get('/categories',[CategoryController::class,'showPcategory']);
Route::patch('/categories/edit/{id}',[CategoryController::class,'edit']);
Route::delete('/categories/delete/{id}',[CategoryController::class,'delete']);
Route::get('/categories/sortByNameDesc',[CategoryController::class,'sortByNameDesc']);
Route::get('/categories/sortByNameAsc',[CategoryController::class,'sortByNameAsc']);


//Reccuring routes

Route::get('/reccurings/show',[ReccuringController::class,'show']);

Route::post('/reccurings/store',[ReccuringController::class,'store']);
Route::patch('/reccurings/update/{id}',[ReccuringController::class,'edit']);
Route::delete('/reccurings/delete/{id}',[ReccuringController::class,'delete']);



//Fixed routes
Route::post('/fixed/store',[FixedController::class,'store']);
Route::delete('/fixed/delete/{id}',[FixedController::class,'delete']);
Route::patch('/fixed/update/{id}',[FixedController::class,'edit']);
Route::get('/fixed/show/{id}',[FixedController::class,'getFixedById']);
Route::get('/fixed/show/key={}',[FixedController::class,'getByKeyId']);
Route::get('/fixed/show/title',[FixedController::class,'getByTitle']);

//Fixed Key
Route::get('/Fixedkeys/show',[Fixedkey::class,'show']);
Route::post('/Fixedkeys/store',[Fixedkey::class,'store']);
Route::patch('/Fixedkeys/update/{id}',[Fixedkey::class,'edit']);
Route::delete('/Fixedkeys/delete/{id}',[Fixedkey::class,'delete']);
Route::get('/Fixedkeys/sortByNameDesc/{id}',[Fixedkey::class,'sortByNameDesc']);
Route::get('/Fixedkeys/sortByNameAsc/{id}',[Fixedkey::class,'sortByNameAsc']);



Route::get('/fixed/filter/{filter}/{value}', [FixedController::class, 'filter']);




Route::post('/login',[adminsController::class,'login']);
// Route::get('/authenticate',[adminsController::class,'access_denied'])->name('access_denied');
Route::post('api/login', 'AuthController@login')->name('api/login');




Route::Get('/profit_goals',[ProfitGoalsController::class,'Get_Profit_Goals']);
Route::Post('/profit_goals',[ProfitGoalsController::class,'Post_Profit_Goals']);
Route::Delete('/profit_goals/{id}',[ProfitGoalsController::class,'Delete_Profit_Goals']);
Route::patch('/profit_goals/{id}',[ProfitGoalsController::class,'Edit_Profit_Goals']);
Route::middleware('auth:')->group(function () {
    Route::get('/fixed/show',[FixedController::class,'show']);
    Route::get('/logout',[adminsController::class,'logout']);
   
});

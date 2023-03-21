<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\ReccuringController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminsController;
use App\Http\Controllers\ProfitGoalsController;
use App\Http\Controllers\FixedController;
use App\Http\Controllers\Fixedkey;
use App\Http\Controllers\MixedController;

/**
 * Router Admin
 */
Route::get('admin',[AuthController::class,'user']);
Route::Get('/admins',[adminsController::class,'Getadmins']);//cheked
Route::post('/admins/name',[adminsController::class,'GetAdminByName']);//cheked
Route::Post('/admins',[adminsController::class,'Postadmins']);//cheked
Route::patch('/admins/{id}',[adminsController::class,'Editadmins']);// fixed
Route::Delete('/admins/{id}',[adminsController::class,'Deleteadmins']);//checked

//AUTH  LOG
Route::post('/login',[AuthController::class,'login'])->name('api/login');
Route::middleware('auth:api')->group(function () {
Route::get('/fixed/show',[FixedController::class,'show']);
    Route::get('/logout',[AuthController::class,'logout']);
   
    
    // /**Router Categories */
    Route::post('/categories',[CategoryController::class,'store']);//checked
    Route::get('/categories',[CategoryController::class,'showPcategory']);//checked
    Route::patch('/categories/{id}',[CategoryController::class,'edit']);//checked
    Route::delete('/categories/{id}',[CategoryController::class,'delete']);//checked
    Route::get('/categories/sortByNameDesc',[CategoryController::class,'sortByNameDesc']);
    Route::get('/categories/sortByNameAsc',[CategoryController::class,'sortByNameAsc']);//checked
    Route::post('/categories/name',[CategoryController::class,'GetCategoriesByName']);//cheked
    
    
    // //Recurring routes
    Route::get('/reccurings',[ReccuringController::class,'show']);//CHECKED
    Route::get('/reccurings/show/sortByDateAsc',[ReccuringController::class,'sortByDateAsc']);//CHECKED
    Route::get('/reccurings/show/sortByDateDesc',[ReccuringController::class,'sortByDateDesc']);//CHECKED
    Route::get('/reccurings/show/sortByAmountDesc',[ReccuringController::class,'sortByAmountDesc']);//CHECKED
    Route::get('/reccurings/show/sortByTitleDesc',[ReccuringController::class,'sortByTitleDesc']);//CHECKED
    Route::Post('/reccurings',[ReccuringController::class,'store']);//CHECKED
    Route::patch('/reccurings/{id}',[ReccuringController::class,'edit']);//CHECKED
    Route::delete('/reccurings/{id}',[ReccuringController::class,'delete']);//CHEKED
    
    
    // //Fixed routes
    Route::get('/fixed',[FixedController::class,'show']);
    Route::post('/fixed',[FixedController::class,'store']);
    Route::delete('/fixed/delete/{id}',[FixedController::class,'delete']);
    Route::patch('/fixed/update/{id}',[FixedController::class,'edit']);
    Route::get('/fixed/filter/{filter}/{value}', [FixedController::class, 'filter']);
    
    // //Fixed Key
    Route::get('/Fixedkeys/show',[Fixedkey::class,'show']);
    Route::post('/Fixedkeys/store',[Fixedkey::class,'store']);
    Route::patch('/Fixedkeys/update/{id}',[Fixedkey::class,'edit']);
    Route::delete('/Fixedkeys/delete/{id}',[Fixedkey::class,'delete']);
    Route::get('/Fixedkeys/sortByNameDesc/{id}',[Fixedkey::class,'sortByNameDesc']);
    Route::get('/Fixedkeys/sortByNameAsc/{id}',[Fixedkey::class,'sortByNameAsc']);
    
    //Profit Goals
    Route::Get('/profit_goals',[ProfitGoalsController::class,'Get_Profit_Goals']);
    Route::Post('/profit_goals',[ProfitGoalsController::class,'Post_Profit_Goals']);
    Route::Delete('/profit_goals/{id}',[ProfitGoalsController::class,'Delete_Profit_Goals']);
    Route::patch('/profit_goals/{id}',[ProfitGoalsController::class,'Edit_Profit_Goals']);
    
    
    //Search 
    Route::post('/search',[MixedController::class,'search']);//checked
    Route::get('/',[MixedController::class,'IncExp']);//checked
    Route::post('/goal',[MixedController::class,'checkProfitGoal']);//checked
    Route::get('/categories',[CategoryController::class,'show']);//checked
    
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;


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


Route::controller(AdminController::class)->group(function(){
    Route::post('/registerAdmin', 'registerAdmin');
    Route::post('/authenticatedAdmin', 'authenticatedAdmin');
    Route::post('/admin-destroy', 'destroy');
    Route::post('/admins', 'index');
});

Route::controller(ContentController::class)->group(function(){
    Route::post('/contents', 'index');
    Route::post('/content', 'createdContent');
    Route::post('/updated-content', 'updatedContent');
    Route::post('/content-destroy', 'destroy');
});

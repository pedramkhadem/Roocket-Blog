<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UploadFileController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('auth/v1')->group(function(){
    Route::post('/login' , [AuthController::class, 'login']);
    Route::post('/register' , [AuthController::class , 'register']);
    // Route::post('/logout', [AuthController::class , 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::group(['prefix'=>'auth/v1' , 'middleware'=>['auth:sanctum']], function(){
    Route::post('/logout' , [AuthController::class , 'logout']);
});

Route::group(['prefix'=>'admin/v1' , 'middleware'=>['auth:sanctum']], function(){
    Route::apiResource('category' , CategoryController::class);
    Route::apiResource('article', ArticleController::class);

    Route::post('media' , [UploadFileController::class , 'uploadImage']);
});



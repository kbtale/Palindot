<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubsetController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Url;

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


$shortenedUrls = Url::all();

foreach($shortenedUrls as $url){
    Route::redirect($url->base_url,$url->to_url, 301);
}

Route::group(['prefix' => 'v1', 'namespace'=>'App\Http\Controllers'], function(){
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class,'login'])->name('auth.login');
    Route::apiResource('urls', UrlController::class);
    
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('logout', [AuthController::class,'logout'])->name('auth.logout');
        Route::apiResource('subsets', SubsetController::class);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

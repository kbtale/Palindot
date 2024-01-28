<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubsetController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Url;
use App\Models\User;

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
    Route::post('urls/create', [UrlController::class,'store'])->name('url.create');
    
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('logout', [AuthController::class,'logout'])->name('auth.logout');
        Route::get('urls', [UrlController::class,'index'])->name('url.index');
        Route::get('urls/{url}', [UrlController::class,'show'])->name('url.show');
        Route::put('urls/{url}', [UrlController::class,'update'])->name('url.update');
        Route::delete('urls/{url}', [UrlController::class,'destroy'])->name('url.delete');
        Route::apiResource('subsets', SubsetController::class);
        Route::apiResource('user', UserController::class);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

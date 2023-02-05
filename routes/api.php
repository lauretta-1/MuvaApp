<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post("user/register", [UserController::class, "register"])->name('register');
    Route::post("user/login", [UserController::class, "login"])->name('login');

    Route::group(['middleware' => 'auth:api'], function () {
        // User
        Route::group(['prefix' => 'users'], function () {
            Route::controller(UserController::class)->group(function (){
                Route::get('', 'index')->name('user.index');
                Route::get('profile', 'show')->name('user.show');
                Route::patch('update', 'update')->name('user.update');
                Route::delete('delete', 'destroy')->name('user.destroy');
                Route::get('logout', 'logout')->name('logout');
            });
        });

        // Category
        Route::group(['prefix' => 'categories'], function () {
            Route::controller(CategoryController::class)->group(function (){
                Route::get('', 'index')->name('index');
                Route::get('profile', 'profile')->name('profile');
                Route::get('logout', 'logout')->name('logout');
            });
        });

        // Post
        Route::group(['prefix' => 'posts'], function () {
            Route::controller(PostController::class)->group(function (){
                Route::get('', 'index')->name('index');
                Route::get('profile', 'profile')->name('profile');
                Route::get('logout', 'logout')->name('logout');
            });
        });

    });
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

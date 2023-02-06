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
    Route::post('user/register', [UserController::class, 'register'])->name('register');
    Route::post('user/login', [UserController::class, 'login'])->name('login');

    Route::group(['middleware' => 'auth:api'], function () {
        // User
        Route::get('users', [UserController::class, 'index'])->name('user.index');
        Route::group(['prefix' => 'user'], function () {
            Route::controller(UserController::class)->group(function (){
                Route::get('profile', 'show')->name('user.show');
                Route::get('posts', 'userPosts')->name('user.posts');
                Route::patch('update', 'update')->name('user.update');
                Route::delete('delete', 'destroy')->name('user.destroy');
                Route::get('logout', 'logout')->name('logout');

                // Forgot and Reset Password
                Route::post('forgot-password', 'sendPasswordResetMail')->name('forgot-password');
                Route::post('password-reset', 'resetPasswordResponse')->name('password-reset');
            });
        });

        // Category
        Route::apiResource('categories', CategoryController::class);

        // Post
        Route::apiResource('posts', PostController::class);

    });
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function(){
    return "Hello boy";
});

// Routes publiques (index)
Route::get('posts', [PostController::class, 'index']);
Route::get('commentaires', [CommentController::class, 'index']);

Route::get('users', [UserController::class, 'index']);
Route::get('categories', [CategoriesController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes protégées
    Route::resource('posts', PostController::class)->except(['index']);
    Route::resource('commentaires', CommentController::class)->except(['index']);

    Route::resource('users', UserController::class)->except(['index']);
    Route::resource('categories', CategoriesController::class)->except(['index']);
    Route::resource('tags', TagController::class);
    Route::post('/{type}/{id}/like', [LikeController::class, 'toggleLike'])
    ->where('type', 'posts|comment');

});

/**
 * Route d'inscription et de connexion
 */
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [LoginController::class, 'login'])->name('login');


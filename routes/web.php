<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [UserAuthController::class, 'dashboard'])->name('dashboard');
Route::get('login', [UserAuthController::class, 'index'])->name('login');
Route::post('custom-login', [UserAuthController::class, 'customLogin'])->name('login.custom');
Route::get('register', [UserAuthController::class, 'registration'])->name('register');
Route::post('custom-registration', [UserAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [UserAuthController::class, 'signOut'])->name('signout');


 Route::resource('posts', PostController::class);

// Route::get('/post/comment', [CommentController::class, 'create'])->name('posts.create');

 Route::get('/posts/{post_id}/comment', [CommentController::class, 'create'])->name('posts.comment');
 //Route::post('/posts/{postId}/comment', [CommentController::class, 'store'])->name('comment.store');
 Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');

//  Route::get('/posts/{postId}', [CommentController::class, 'show']);
 Route::get('/posts/{postId}/comments', [CommentController::class, 'show'])->name('posts.show_comments');

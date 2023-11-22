<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'getFormLogin'])->name('get_form_login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'getFormRegister'])->name('get_form_register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/forgot-password', [AuthController::class, 'getFormForgotPassword'])->name('get_form_forgot_password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'getFormResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/email/verify', [AuthController::class, 'getVerificationNotice'])
    ->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendingVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//OAuth routes
Route::get('/auth/redirect/github', [AuthController::class, 'getRedirectGithubLogin'])->name('redirect_github');
Route::get('/auth/callback/github', [AuthController::class, 'loginWithGithub'])->name('login_github');

Route::middleware('userLogin')->group(function () {
    Route::get('/', [PostController::class, 'home'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // Post routes
    Route::prefix('posts')->name('posts.')->group(function () {
        // View Routes
        // Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::get('/edit', [PostController::class, 'edit'])->name('edit');
        Route::get('/{id}', [PostController::class, 'detail'])->name('detail');

        // Action Routes
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::put('/{id}', [PostController::class], 'update')->name('update');
        Route::delete('/{id}', [PostController::class], 'delete')->name('delete');
        Route::post('/share', [PostController::class, 'share'])->name('share');
    });

    // User routes
    Route::prefix('users')->name('users.')->group(function () {
        // View routes
        Route::get('/account/profile/{userId}', [UserController::class, 'getProfileView'])->name('get_profile_view');
        Route::get('/account/profile/edit', [UserController::class, 'getEditView'])->name('get_edit_form');

        // Action routes
        Route::post('/account/profile/update/{userId}', [UserController::class, 'update'])->name('update');
        Route::post('/{fromUserId}/follwing/{toUserId}', [UserController::class, 'follwing'])->name('following');
        Route::post('/{fromUserId}/follwing/{toUserId}/cancel', [UserController::class, 'cancelSendingFollowing'])->name('cancel_following');
    });

    Route::prefix('comments')->name('comments.')->group(function () {
        // Action routes
        Route::post('/create/user/{userId}/post/{postId}', [CommentController::class, 'create'])->name('create');
    });
});

// Middleware cho notification fcm
Route::group(['middleware' => 'auth'], function () {
    Route::post('/store-token', [NotificationController::class, 'updateDeviceToken'])->name('store.token');
});

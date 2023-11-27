<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
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

// Authentication
Route::get('/login', [AuthController::class, 'getFormLogin'])->name('get_form_login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'getFormRegister'])->name('get_form_register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Forgot password
Route::get('/forgot-password', [AuthController::class, 'getFormForgotPassword'])->name('get_form_forgot_password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'getFormResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Verify email
Route::get('/email/verify', [AuthController::class, 'getVerificationNotice'])
    ->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendingVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//OAuth routes
Route::get('/auth/redirect/github', [AuthController::class, 'getRedirectGithubLogin'])->name('redirect_github');
Route::get('/auth/callback/github', [AuthController::class, 'loginWithGithub'])->name('login_github');

Route::middleware(['userLogin', 'verified'])->group(function () {
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
        Route::post('/{postId}/user/{userId}/share}', [PostController::class, 'share'])->name('share');
        Route::post('/{postId}/user/{userId}/like', [PostController::class, 'likePost'])->name('like_post');
    });

    // User routes
    Route::prefix('users')->name('users.')->group(function () {
        // View routes
        Route::get('/account/profile/{userId}', [UserController::class, 'getProfileView'])->name('get_profile_view');
        Route::get('/account/profile/edit', [UserController::class, 'getEditView'])->name('get_edit_form');
        Route::get('/account/profile/{userId}/requests', [UserController::class, 'getRequestFollowers'])->name('get_request_followers');

        // Action routes
        Route::post('/account/profile/update/{userId}', [UserController::class, 'update'])->name('update');
        Route::post('/{fromUserId}/follwing/{toUserId}', [UserController::class, 'following'])->name('following');
        Route::post('/{fromUserId}/follwing/{toUserId}/accept', [UserController::class, 'acceptFollowing'])->name('accept_following');
        Route::post('/{fromUserId}/follwing/{toUserId}/reject', [UserController::class, 'rejectFollowing'])->name('reject_following');
        Route::post('/{fromUserId}/follwing/{toUserId}/cancel', [UserController::class, 'cancelSendingFollowing'])->name('cancel_sending_following');
    });

    // Comment routes
    Route::prefix('comments')->name('comments.')->group(function () {
        // Action routes
        Route::post('/create/user/{userId}/post/{postId}', [CommentController::class, 'create'])->name('create');
    });

    // Payment routes
    Route::prefix('payment')->name('payment.')->group(function () {
        // View routes
        Route::get('/package', [PaymentController::class, 'getPaymentPackageView'])->name('get_payment_package_view');
        Route::get('/response', [PaymentController::class, 'getPaymentResponseView'])->name('get_payment_response_view');

        // Action routes
        Route::post('/vnpay_payment', [PaymentController::class, 'vnpayPayment'])->name('vnpay_payment');
    });

    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // View routes
        Route::get('/', [NotificationController::class, 'getNotificationPage'])->name('get_notification_page');

        // Action routes
    });
});

// Middleware cho notification fcm
Route::group(['middleware' => 'auth'], function () {
    Route::post('/store-token', [NotificationController::class, 'storeDeviceToken'])->name('store.token');
});

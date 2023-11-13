<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;

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


Route::middleware('userLogin')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // Post route
    Route::prefix('posts')->name('posts.')->group(function () {
        // Views Routes
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::get('/edit', [PostController::class, 'edit'])->name('edit');
        Route::get('/post/{id}', [PostController::class, 'detail'])->name('detail');

        // Action Routes
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::put('/post/{id}', [PostController::class], 'update')->name('update');
        Route::delete('/post/{id}', [PostController::class], 'delete')->name('delete');
    });
});

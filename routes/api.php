<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ShortURLController;
use App\Http\Controllers\UserLinkController;
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

Route::post('/links', [ShortURLController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

Route::post('/refresh', [AuthController::class, 'refresh']);


Route::post('/password/email', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth:api')->group(function () {

    Route::get('/links/{user_id?}', [UserLinkController::class, 'getAllLinks']);
    Route::put('/links/{short_code}', [UserLinkController::class, 'update']);

    Route::delete('/links/{short_code}', [UserLinkController::class, 'destroy']);

});

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'listUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
});


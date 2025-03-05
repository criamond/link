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

Route::post('/shorten', [ShortURLController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

Route::post('/refresh', [AuthController::class, 'refresh']);


Route::post('/password/email', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function () {
    Route::get('/get-all-links/{user_id?}', [UserLinkController::class, 'getAllLinks']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::put('/link/{shortCode}', [UserLinkController::class, 'update']);

    Route::get('/stats/{shortCode}', [ShortURLController::class, 'stats']);
    Route::delete('/delete/{shortCode}', [ShortURLController::class, 'destroy']);

});

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'listUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::delete('/admin/links/{id}', [AdminController::class, 'deleteLink']);
});


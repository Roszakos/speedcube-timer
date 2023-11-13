<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolveController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/solve', [SolveController::class, 'store']);
    Route::put('/solve/{solve:hash}', [SolveController::class, 'update']);
    Route::delete('/solve/{solve:hash}', [SolveController::class, 'destroy']);
    Route::get('/session/{hash}', [SessionController::class, 'getSessionData']);
    Route::get('/session', [SessionController::class, 'getAllSessions']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user', [UserController::class, 'destroy']);
    Route::put('/password', [PasswordController::class, 'changePassword']);



    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return ['email' => 'sent'];
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/email/verify', function () {
    return response([
        'email_verified' => false
    ], 403);
})->middleware('auth:sanctum')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //return $request;
    $request->fulfill();

    return ['success' => true];
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
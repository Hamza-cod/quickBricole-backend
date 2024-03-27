<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredHandymanController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\HandymanController;
use Illuminate\Support\Facades\Route;
Route::prefix('api')->group(function () {
Route::post('/register/user', [RegisteredUserController::class, 'store'])->name('register');
Route::post('/register/handyman', [RegisteredHandymanController::class, 'store'])->name('register.bricoler');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');
Route::middleware(['auth:sanctum,handyman'])->group(function () {
    
  Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                  ->middleware('auth')
                  ->name('logout');
  Route::post('/checkPasswordHandyman/{handyman}', [HandymanController::class, 'checkHandymansPassword'])
                  ->middleware('auth')
                  ->name('checkhandymanpassword');
});
});
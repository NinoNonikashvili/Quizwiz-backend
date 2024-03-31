<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');



Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/verify', [VerifyEmailController::class, 'index'])->name('verify-email');
Route::get('/resend-verify-email', [VerifyEmailController::class, 'resendEmail'])->name('resend-verify-email');

Route::post('/register', [UserController::class, 'register'])->name('register');

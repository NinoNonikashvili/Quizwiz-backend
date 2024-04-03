<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware(['auth:sanctum', 'verified']);

Route::post('/login', [UserController::class, 'login'])->middleware('guest')->name('login');

Route::get(
	'/email/verify/{id}/{hash}',
	[VerifyEmailController::class, 'verifyEmail']
)->middleware('guest')->name('verification.verify');

Route::get('/email/verification-notification/{user}', [VerifyEmailController::class, 'resendEmail'])->middleware('guest')->name('verification.send');

Route::post('/register', [UserController::class, 'register'])->middleware('guest')->name('register');

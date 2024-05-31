<?php

use App\Http\Controllers\PasswordController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::middleware('guest')->group(function () {
	Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

	Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('password.email');

	Route::post('/login', [UserController::class, 'login'])->name('login');

	Route::get(
		'/email/verify/{id}/{hash}',
		[VerifyEmailController::class, 'verifyEmail']
	)->name('verification.verify');

	Route::get('/email/verification-notification/{user}', [VerifyEmailController::class, 'resendEmail'])->name('verification.send');

	Route::post('/register', [UserController::class, 'register'])->name('register');
});
Route::get('/check-auth-state', [UserController::class, 'checkState'])->name('check-auth-state');

Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
Route::controller(QuizController::class)->group(function () {
	Route::get('/quizes', 'index')->name('get-quizes')->middleware('auth');
	Route::get('/quizes/{quiz}', 'show')->name('show-single-quiz')->middleware('auth');
	Route::get('/similar-quizes/{quiz}', 'similarQuizes')->name('similar-quizes')->middleware('auth');
	Route::get('/test/{quiz}', 'quizTest')->name('single-quiz-test')->middleware('auth');
	Route::post('/results/{quiz}', 'calculateResults')->name('calculate-results')->middleware('auth');
	Route::get('/categories', 'getCategories')->name('get-categories')->middleware('auth');
	Route::get('/levels', 'getLevels')->name('get-levels')->middleware('auth');
});

Route::get('footer-data', [AppController::class, 'getFooterData'])->name('get-footer-data');

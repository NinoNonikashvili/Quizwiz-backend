<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::post('/reset-password', function (Request $request) {
	$request->validate([
		'token'    => 'required',
		'email'    => 'required|email',
		'password' => 'required|min:4|confirmed',
	]);

	$status = Password::reset(
		$request->only('email', 'password', 'password_confirmation', 'token'),
		function (User $user, string $password) {
			$user->forceFill([
				'password' => Hash::make($password),
			])->setRememberToken(Str::random(60));

			$user->save();

			event(new PasswordReset($user));
		}
	);

	return response($status);
})->middleware('guest')->name('password.update');

Route::post('/forgot-password', function (Request $request) {
	$request->validate(['email' => 'required|email']);

	$status = Password::sendResetLink(
		$request->only('email')
	);

	return response($status . 'send reset email');
})->middleware('guest')->name('password.email');

Route::get('/user', function (Request $request) {
	return auth()->user() ?? 'no user';
})->middleware();

Route::post('/login', [UserController::class, 'login'])->middleware('guest')->name('login');

Route::get(
	'/email/verify/{id}/{hash}',
	[VerifyEmailController::class, 'verifyEmail']
)->middleware('guest')->name('verification.verify');

Route::get('/email/verification-notification/{user}', [VerifyEmailController::class, 'resendEmail'])->middleware('guest')->name('verification.send');

Route::post('/register', [UserController::class, 'register'])->middleware('guest')->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/log', function () {
	// Auth::logout();
	return response('logged out ' . auth()->user());
});
Route::get('/check-auth-state', [UserController::class, 'checkState'])->name('check-auth-state');

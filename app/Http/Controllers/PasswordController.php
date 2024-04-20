<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
	public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
	{
		$status = Password::sendResetLink(
			$request->only('email')
		);

		return response()->json([
			'status' => 'RESET_LINK_SENT',
		]);
	}

	public function resetPassword(ResetPasswordRequest $request): JsonResponse
	{
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

		return $status === Password::PASSWORD_RESET
		? response()->json([
			'status' => 'RESET_SUCCESS',
		])
		: response()->json([
			'status' => 'RESET_LINK_EXPIRED',
		]);
	}
}

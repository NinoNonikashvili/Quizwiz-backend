<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
	public function verifyEmail(CustomEmailVerificationRequest $request): JsonResponse
	{
		if (!$request->hasValidSignature(false)) {
			return response()->json([
				'status' => 'VERIFICATION_LINK_EXPIRED',
			]);
		}
		$request->fulfill();

		return response()->json([
			'status' => 'VERIFICATION_SUCCESS',
		]);
	}

	public function resendEmail(Request $request): JsonResponse
	{
		if (str_contains(($request->route('user')), '@')) {
			$user = User::where('email', $request->route('user'))->first();
		} else {
			$user = User::find($request->route('user'));
		}

		if ($user) {
			$user->sendEmailVerificationNotification();
			return response()->json([
				'status' => 'VERIFICATION_LINK_SENT',
			]);
		}

		return response()->json([
			'status' => 'LOGIN_WRONG_INPUT',
		]);
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomEmailVerificationRequest;
use App\Models\User;

class VerifyEmailController extends Controller
{
	public function verifyEmail(CustomEmailVerificationRequest $request)
	{
		if (!$request->hasValidSignature(false)) {
			return response('expired', 403);
		}
		$request->fulfill();

		return 'email verified';
	}

	public function resendEmail(Request $request)
	{
		if (str_contains(($request->route('user')), '@')) {
			$user = User::where('email', $request->route('user'))->first();
		} else {
			$user = User::find($request->route('user'));
		}

		if ($user) {
			$user->sendEmailVerificationNotification();
			return response('email was resent', 200);
		}

		return response('user was not found', 404);
	}
}

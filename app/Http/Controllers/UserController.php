<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	/**
	 * if validation fails sends 422 error
	 */
	public function register(CreateUserRequest $request): JsonResponse
	{
		$user = User::create(['username' => $request['username'], 'email' => $request['email'], 'password' => $request['password']]);

		if ($user) {
			event(new Registered($user));

			return response()->json([
				'status' => 'VERIFICATION_LINK_SENT',
			]);
		}
		return response()->json([
			'status' => 'REGISTRATION_WRONG_INPUT',
			'text'   => 'user already exists or data provided is invalid',
		], 422);
	}

	/**
	 * if validation fails sends 422 error
	 */
	public function login(LoginUserRequest $request)
	{
		$user = User::where('email', $request['email'])->first();

		if ($user && $user->email_verified_at !== null) {
			if (Auth::attempt(['email'=> $request['email'], 'password'=>$request['password']], $request['remember'])) {
				$request->session()->regenerate();
				return  response()->json([
					'status' => 'LOGIN_SUCCESS',
					'data'   => new UserResource(auth()->user()),
				]);
			}
			return response()->json([
				'status' => 'LOGIN_WRONG_INPUT',
				'text'   => 'user could not be found with these credentials',
			], 404);
		} elseif ($user && $user->email_verified_at === null) {
			return response()->json([
				'status' => 'NOT_VERIFIED',
			], 403);
		}

		return response()->json([
			'status' => 'LOGIN_WRONG_INPUT',
			'text'   => 'user could not be found with these credentials',
		], 404);
	}

	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json([
			'status'=> 'LOGOUT_SUCCESS',
		]);
	}

	public function checkState(): JsonResponse
	{
		$user = auth()->check() ? new UserResource(auth()->user()) : null;
		return response()->json([
			'data' => $user,
		]);
	}
}

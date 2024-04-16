<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
	/**
	 * if validation fails sends 422 error
	 */
	public function register(CreateUserRequest $request): Response
	{
		$user = User::create(['username' => $request['username'], 'email' => $request['email'], 'password' => $request['password']]);
		if ($user) {
			event(new Registered($user));

			return response('Verification link is sent to your email ' . $request->user(), 200)->header('Content-Type', 'application/json');
		}
		return response('invalid data', 422)->header('Content-Type', 'application/json');
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
				return new UserResource(auth()->user());
			}
			return response('wrong credentials', 404)->header('Content-Type', 'application/json');
		} elseif ($user && $user->email_verified_at === null) {
			return response('verify email', 403)->header('Content-Type', 'application/json');
		}

		return response('user was not found', 404)->header('Content-Type', 'application/json');
	}

	public function logout()
	{
		Auth::logout();
		return response()->noContent();
	}

	public function checkState()
	{
		return auth()->user() ? new UserResource(auth()->user()) : null;
	}
}

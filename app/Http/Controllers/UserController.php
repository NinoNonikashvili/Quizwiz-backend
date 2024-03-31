<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;


class UserController extends Controller
{
	/**
	 * if validation fails sends 422 error
	 */
	public function register(CreateUserRequest $request): Response
	{
		if (User::create(['username' => $request['username'], 'email' => $request['email'], 'password' => $request['password']])) {
			$link = VerifyEmailController::generateTemporaryUrl('verify-email', now()->addMinutes(120), ['email' => $request['email']] );
			 //send email
			//if success
			return response('Verification link is sent to your email '.$link , 200)->header('Content-Type', 'application/json');
			//else return 500
			
		}
		return response('invalid data', 422)->header('Content-Type', 'text/plain');
	}

	/**
	 * if validation fails sends 422 error
	 */
	public function login(LoginUserRequest $request): Response
	{
		if (Auth::attempt(['email'=> $request['email'], 'password'=>$request['password']], $request['remember'])) {
			$request->session()->regenerate();
			return response('logged in successfully', 200)->header('Content-Type', 'application/json');
		}
		return response('user was not found', 404)->header('Content-Type', 'application/json');
	}
}

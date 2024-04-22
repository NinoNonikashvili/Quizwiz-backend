<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'username'         => 'required|min:3|unique:users,username',
			'email'            => 'required|email|unique:users,email|',
			'password'         => 'required|min:3',
			'confirm_password' => 'required|same:password',
			'terms'            => 'required|accepted',
		];
	}
}

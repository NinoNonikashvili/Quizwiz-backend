<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
		Nova::userTimezone(function () {
			return 'UTC';
		});
		Nova::createUserUsing(function ($command) {
			$username = $command->ask('Username');
			$email = $command->ask('Email Address');
			$password = $command->secret('Password');
			if (strlen($username) < 3) {
				$command->error('username must be at least 4 chars');
			} elseif (preg_match('/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/', $email) !== 1) {
				$command->error('email must be valid');
			} elseif (strlen($password) < 3) {
				$command->error('password must be at least 4 chars');
			} else {
				return [
					$username,
					$email,
					$password,
				];
			}
		}, function ($name, $email, $password) {
			(new User)->forceFill([
				'username'          => $name,
				'email'             => $email,
				'password'          => Hash::make($password),
				'email_verified_at' => now(),
			])->save();
		});
	}

	/**
	 * Register the Nova routes.
	 *
	 * @return void
	 */
	protected function routes()
	{
		Nova::routes()
				->withAuthenticationRoutes()
				->withPasswordResetRoutes()
				->register();
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 *
	 * @return void
	 */
	protected function gate()
	{
		Gate::define('viewNova', function ($user) {
    return true;
});
	}

	/**
	 * Get the dashboards that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	protected function dashboards()
	{
		return [
			new \App\Nova\Dashboards\Main,
		];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	public function tools()
	{
		return [];
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}
}

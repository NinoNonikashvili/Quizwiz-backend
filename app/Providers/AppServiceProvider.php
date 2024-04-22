<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		VerifyEmail::createUrlUsing(function ($notifiable) {
			return URL::temporarySignedRoute(
				'verification.verify',
				Carbon::now()->addMinutes(1),
				[
					'id'   => $notifiable->getKey(),
					'hash' => sha1($notifiable->getEmailForVerification()),
				],
				absolute:false
			);
		});

		VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
			$front_url = config('app.frontend_url') . preg_replace('/\/api/', '/login', $url, 1);
			$part = explode('verify/', $front_url);
			$subpart = explode('/', $part[1]);
			$id = $subpart[0];
			$username = User::find($id)->username;
			return (new MailMessage)
				->theme('custom')
				->greeting('Verify your email address to get started')
				->subject('Please, verify your email')
				->line('Hi, ' . $username)
				->line("You're almost there! To complete your sign up, please verify your email address.")
				->action('Verify now', $front_url)
				->salutation('  ');
		});

		ResetPassword::toMailUsing(function ($notifiable, $token) {
			$url = url(route('password.update', [
				'token' => $token,
				'email' => $notifiable->getEmailForPasswordReset(),
			], false));
			$front_url = preg_replace('/:8000\/api/', ':5173', $url, 1);
			$expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
			// $email = $notifiable->getEmailForPasswordReset();
			return (new MailMessage)
			->theme('custom')
			->greeting('Reset your password')
			->subject('Reset Password Notification')
			->line('You are receiving this email because we received a password reset request for your account.')
			->line("this link will expire in $expire minutes")
			->action('Reset Password', $front_url)
			->salutation('  ');
		});
	}
}

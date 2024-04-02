<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

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
				Carbon::now()->addMinutes(120),
				[
					'id'   => $notifiable->getKey(),
					'hash' => sha1($notifiable->getEmailForVerification()),
				],
				absolute:false
			);
		});
		VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
			$front_url = env('FRONTEND_URL', 'http://127.0.0.1:5173') . preg_replace('/\/api/', '/login', $url, 1);
			return (new MailMessage)
				->greeting('Hello!')
				->subject('Hi bro! it"s me cool developer from laravel ')
				->line('Click the button below to verify your email address.')
				->action('Verify Email Address', $front_url);
		});
	}
}

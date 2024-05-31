<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (ValidationException $e, Request $request) {
			if (str_contains($request->url(), 'register')) {
				return response()->json([
					'status'    => 'REGISTRATION_WRONG_INPUT',
					'text'      => 'user already exists or data provided is invalid',
				], 422);
			} if ($request->is('nova-api/*')) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 422);
            }
		});
	})->create();

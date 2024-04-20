<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AppController extends Controller
{
	public function getFooterData(): JsonResponse
	{
		return response()->json([
			'data' => [
				'email'  => 'example@email.com',
				'phone'  => '+995 889 990 934',
				'socials'=> [
					'Facebook'  => '#',
					'Instagram' => '#',
				],
			],
		]);
	}
}

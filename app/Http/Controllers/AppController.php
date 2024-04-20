<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppInfoResource;
use Illuminate\Http\JsonResponse;
use App\Models\AppInfo;

class AppController extends Controller
{
	public function getFooterData(): JsonResponse
	{
		return response()->json([
			'data' => new AppInfoResource(AppInfo::first()),
		]);
	}
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): AnonymousResourceCollection
	{
		$db = Quiz::with(['categories', 'level'])->withCount('users');

		if (auth()->check()) {
			$db->with('users', function ($query) {
				$query->where('user_id', auth()->user()->id);
			});
		}
		if ($request->has('totalPage')) {
			$per_page = 9 * $request->input('totalPage');
			$quizes = $db->simplePaginate($per_page);
		} else {
			$quizes = $db->simplePaginate(9);
		}
		return QuizResource::collection($quizes);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function singleQuizInfo(): QuizResource
	{
		return new QuizResource(Quiz::find(1));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function singleQuizTest(Request $request)
	{
	}
}

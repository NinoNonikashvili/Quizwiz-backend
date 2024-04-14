<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): AnonymousResourceCollection
	{
		$db = Quiz::with(['categories', 'level'])->withCount('users');

		$db->withFilters();

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

	public function getCategories(): Collection
	{
		return Category::all();
	}

	public function getLevels(): Collection
	{
		return Level::all();
	}
}

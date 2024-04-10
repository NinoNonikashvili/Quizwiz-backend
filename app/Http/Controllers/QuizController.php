<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return QuizResource::collection(Quiz::with(['categories', 'level', 'users'])->get());
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function singleQuizInfo()
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

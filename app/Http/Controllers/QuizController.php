<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizTestResource;
use App\Http\Resources\SingleQuizResource;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$db = Quiz::with(['categories', 'level'])->withCount('users');

		$db->withFilters();
		$db->withUserQuizes();
		$db->withUserResults();

		if ($request->has('totalPage')) {
			$per_page = 9 * $request->input('totalPage');
			$quizes = $db->simplePaginate($per_page);
		} else {
			$quizes = $db->simplePaginate(9);
		}
		return QuizResource::collection($quizes);
	}

	public function show(Quiz $quiz): SingleQuizResource
	{
		return new SingleQuizResource($quiz);
	}

	public function similarQuizes(Quiz $quiz): AnonymousResourceCollection
	{
		$db = Quiz::where('id', '!=', $quiz->id);

		$db->withUserQuizes();
		$db->withUserResults();
		$db->withSimilar($quiz);

		$quizes = $db->take(3)->get();
		return QuizResource::collection($quizes);
	}

	public function quizTest(Quiz $quiz): AnonymousResourceCollection
	{
		return QuizTestResource::collection($quiz->questions()->with('answers')->get());
	}

	public function calculateResults(Quiz $quiz)
	{
		$quiz = $quiz->questions()->with('answers')->get();
		$result_schema = [];

		foreach ($quiz as $question) {
			$questionId = $question['id'];
			$correctAnswerIds = [];

			foreach ($question['answers'] as $answer) {
				if ($answer['isCorrect'] == 1) {
					$correctAnswerIds[] = $answer['id'];
				}
			}

			$result_schema[$questionId]['correct_answer'] = $correctAnswerIds;
			$result_schema[$questionId]['point'] = $question->points;
		}
		return $result_schema;
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

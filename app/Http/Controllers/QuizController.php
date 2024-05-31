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
		$db->withQuestions();
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

	public function calculateResults(Quiz $quiz, Request $request)
	{
		$test = $quiz->questions()->with('answers')->get();
		$result_schema = [];
		$correct = 0;
		$wrong = 0;
		$total = 0;
		if ($request->has('data')) {
			return $request->input('data');
		}

		foreach ($test as $question) {
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
		foreach ($result_schema as $question_id =>$question_data) {
			if ($request->has($question_id)) {
				if (!empty(array_diff($question_data['correct_answer'], $request->input($question_id)))) {
					$wrong++;
				} else {
					$correct++;
					$total += $question_data['point'];
				}
			} else {
				$wrong++;
			}
		}
		$result = [
			'time'            => $request->input('time'),
			'correct_answers' => $correct,
			'wrong_answers'   => $wrong];

		$user = auth()->user() ? auth()->user()->id : null;

		$quiz->users()->attach($user, ['time' => $request->input('time'), 'result' => $total]);
		return $result;
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

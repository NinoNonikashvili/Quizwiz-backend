<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Quiz;

class QuizSimilarTest extends TestCase
{
	protected $user;

	protected function setUp(): void
	{
		parent::setUp();

		$this->user = User::find(1);
	}

	public function test_return_first_three_quizes_with_same_category_if_guest()
	{
		$response = $this->get('api/similar-quizes/3');
		$quizes = $response->json();

		$categories_of_requested_quiz = collect(Quiz::find(3)->categories)->pluck('id')->toArray();

		foreach ($quizes['data'] as $quiz) {
			$quiz_cat_ids = array_map(function ($obj) {
				return $obj['id'];
			}, $quiz['categories']);
			$this->assertNotEmpty(array_intersect($quiz_cat_ids, $categories_of_requested_quiz));
		}

		$this->assertLessThanOrEqual(3, count($quizes['data']));
		$response->assertSuccessful();
	}

	public function test_return_first_three_quizes_with_same_category_not_yet_written_by_user_if_auth_user()
	{
		$response = $this->actingAs($this->user)->get('api/similar-quizes/3');
		$quizes = $response->json();
		$categories_of_requested_quiz = collect(Quiz::find(3)->categories)->pluck('id')->toArray();

		foreach ($quizes['data'] as $quiz) {
			$quiz_cat_ids = array_map(function ($obj) {
				return $obj['id'];
			}, $quiz['categories']);
			$this->assertNotEmpty(array_intersect($quiz_cat_ids, $categories_of_requested_quiz));
			$this->assertTrue($quiz['completed'] === false);
		}

		$this->assertLessThanOrEqual(3, count($quizes['data']));
		$response->assertSuccessful();
	}
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class QuizFilterTest extends TestCase
{
	protected $user;

	protected function setUp(): void
	{
		parent::setUp();

		$this->user = User::find(1);
	}

	public function test_return_first_nine_quizes_if_no_filter_provided()
	{
		$response = $this->get('/api/quizes');

		$quizes = $response->json();
		$this->assertIsArray($quizes);
		$this->assertNotEmpty($quizes);

		foreach ($quizes['data'] as $quiz) {
			$this->assertArrayHasKey('id', $quiz);
			$this->assertArrayHasKey('title', $quiz);
			$this->assertArrayHasKey('image', $quiz);
			$this->assertArrayHasKey('categories', $quiz);
			$this->assertArrayHasKey('level', $quiz);
			$this->assertArrayHasKey('total_users', $quiz);
			$this->assertArrayHasKey('points', $quiz);
			$this->assertArrayHasKey('completed', $quiz);
			$this->assertArrayHasKey('date', $quiz);
			$this->assertArrayHasKey('total_time', $quiz);
		}
		$this->assertLessThanOrEqual(9, count($quizes['data']));
		$response->assertSuccessful();
	}

	public function test_return_first_max_nine_quiz_within_provided_category_list()
	{
		$response = $this->get('/api/quizes?cat[]=2&cat[]=5');

		$quizes = $response->json();
		$this->assertIsArray($quizes);
		$this->assertNotEmpty($quizes);

		foreach ($quizes['data'] as $quiz) {
			$quiz_cat_ids = array_map(function ($obj) {
				return $obj['id'];
			}, $quiz['categories']);
			$this->assertNotEmpty(array_intersect($quiz_cat_ids, [2, 5]));
		}

		$this->assertLessThanOrEqual(9, count($quizes['data']));
		$response->assertSuccessful();
	}

	public function test_return_first_max_nine_quiz_within_provided_level_list()
	{
		$response = $this->get('/api/quizes?level[]=1&level[]=5');

		$quizes = $response->json();
		$this->assertIsArray($quizes);
		$this->assertNotEmpty($quizes);

		foreach ($quizes['data'] as $quiz) {
			$this->assertContains($quiz['level']['id'], [1, 5]);
		}
		$this->assertLessThanOrEqual(9, count($quizes['data']));
		$response->assertSuccessful();
	}

	public function test_return_first_max_nine_quiz_with_name_including_search_keyword()
	{
		$response = $this->get('/api/quizes?search=king');

		$quizes = $response->json();
		$this->assertIsArray($quizes);
		$this->assertNotEmpty($quizes);
		foreach ($quizes['data'] as $quiz) {
			$this->assertTrue(str_contains(strtolower($quiz['title']), strtolower('king')));
		}
		$this->assertLessThanOrEqual(9, count($quizes['data']));
		$response->assertSuccessful();
	}

	public function test_return_first_max_nine_quiz_that_user_has_written()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?my_quizes=true');

		$quizes = $response->json();
		foreach ($quizes['data'] as $quiz) {
			$this->assertTrue($quiz['completed']);
		}
	}

	public function test_return_first_max_nine_quiz_sorted_az_if_az_sort_provided()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?sort_alphabet=asc');

		$quizes = $response->json();
		$titles = collect($quizes['data'])->pluck('title')->toArray();
		$titles_to_sort = $titles;
		sort($titles_to_sort);
		$this->assertSame($titles, $titles_to_sort);
	}

	public function test_return_first_max_nine_quiz_sorted_za_if_za_sort_provided()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?sort_alphabet=desc');

		$quizes = $response->json();
		$titles = collect($quizes['data'])->pluck('title')->toArray();
		$titles_to_sort = $titles;
		rsort($titles_to_sort);
		$this->assertSame($titles, $titles_to_sort);
	}

	public function test_return_first_max_nine_quiz_sorted_newset_if_newest_sort_provided()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?sort_date=asc');

		$quizes = $response->json();
		$date = collect($quizes['data'])->pluck('date')->toArray();
		$date_to_sort = $date;
		sort($date_to_sort);
		$this->assertSame($date, $date_to_sort);
	}

	public function test_return_first_max_nine_quiz_sorted_oldest_if_oldest_sort_provided()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?sort_date=desc');

		$quizes = $response->json();
		$date = collect($quizes['data'])->pluck('date')->toArray();
		$date_to_sort = $date;
		rsort($date_to_sort);
		$this->assertSame($date, $date_to_sort);
	}

	public function test_return_first_max_nine_quiz_sorted_most_popular_if_most_popular_sort_provided()
	{
		$response = $this->actingAs($this->user)->get('/api/quizes?sort_popular');

		$quizes = $response->json();
		$written_count = collect($quizes['data'])->pluck('total_users')->toArray();
		$written_count_to_sort = $written_count;
		rsort($written_count_to_sort);
		$this->assertSame($written_count, $written_count_to_sort);
	}
}

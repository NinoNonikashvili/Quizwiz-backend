<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		if (!Storage::exists('public/quizImages')) {
			Storage::makeDirectory('public/quizImages');
		}
		return [
			'title'        => fake()->name(),
			'instructions' => fake()->sentence(),
			'excerpt'      => fake()->sentence(),
			'image'        => 'quizImages/' . fake()->image('public/storage/quizImages', 640, 480, null, false),
		];
	}
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'question'             => fake()->sentence(),
			'points'               => fake()->numberBetween(1, 15),
			'correct_answers'      => fake()->numberBetween(1, 3),
		];
	}
}

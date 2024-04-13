<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Level;
use App\Models\Category;
use App\Models\Answer;
use App\Models\Question;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		Level::factory()->create();

		Quiz::factory(20)->hasAttached(
			Category::factory()->count(2),
		)->hasAttached(
			User::factory()->create([
				'email_verified_at' => '2024-04-13 15:11:39',
				'password'          => '1234',
			]),
			['time'=> '12min', 'result' => 10]
		)->create([
			'level_id'=> 1,
		]);
		Quiz::factory(1)->hasAttached(
			Category::factory()->count(2),
		)->hasAttached(
			User::factory()->create(),
			['time'=> '12min', 'result' => 10]
		)->create([
			'level_id'=> 1,
		]);
		Question::factory(3)->hasAttached(
			Answer::factory()->count(3),
			['quiz_id' => 1, 'answer_status' => false]
		)->create();
	}
}

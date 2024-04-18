<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Level;
use App\Models\Category;
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
		User::factory()->create([
			'username' => 'nina',
			'email'    => 'nina@gmail.com',
			'password' => '1234',
		]);

		Quiz::factory(15)->hasAttached(
			Category::factory()->count(2),
		)->hasAttached(
			User::factory()->create(),
			['time'=> 12, 'result' => 10]
		)->create([
			'level_id'=> 1,
			'time'    => 15,
		]);
		Question::factory(3)->hasAnswers(3)
		->create([
			'quiz_id'=> 1,
		]);
	}
}

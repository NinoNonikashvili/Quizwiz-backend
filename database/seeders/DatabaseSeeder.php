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
		// User::factory(10)->create();

		Level::factory(2)->create();
		User::factory()->hasQuizes(3)->create([
			'username' => 'nino',
			'email'    => 'test@example.com',
		]);
		Answer::factory(6)->create();
		Question::factory(3)->hasAnswers(4)->hasQuizes(2)->create();

		Category::factory(2)->hasQuizes(3)->create();
		Quiz::factory(4)->hasUsers(2)->hasCategories(2)->create();
	}
}

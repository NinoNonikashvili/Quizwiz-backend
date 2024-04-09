<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Level;
use App\Models\Category;
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

		User::factory()->create([
			'username' => 'nino',
			'email'    => 'test@example.com',
		]);
		Category::factory(2)->hasQuizes(3)->create();
		Level::factory(2)->hasQuizes(3)->create();
		Quiz::factory(4)->hasCategories(2)->create([
			'level_id' => Level::first(),
		]);
	}
}

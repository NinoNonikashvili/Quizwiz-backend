<?php

namespace Database\Seeders;

use App\Models\AppInfo;
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
		User::factory()->create([
			'username' => 'nina',
			'email'    => 'nina@gmail.com',
			'password' => '1234',
		]);
		Level::factory()->create();

		Quiz::factory(15)->hasAttached(
			Category::factory()->count(2),
		)->hasAttached(
			User::find(1),
			['time'=> 12, 'result' => 10]
		)->create([
			'level_id'=> 1,
			'time'    => 15,
		]);
		Question::factory(3)->hasAnswers(3)
		->create([
			'quiz_id'=> 1,
		]);
		AppInfo::factory()->create([
			'email'     => 'example@email.com',
			'phone'     => '+995 889 990 934',
			'Facebook'  => '#',
			'Instagram' => '#',
		]);
	}
}

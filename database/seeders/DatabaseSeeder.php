<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call(GradeSeeder::class);

		// User::factory(10)->create();

		// Без событий только для тестового пользователя; GradeSeeder нужны события модели (slug).
		Model::withoutEvents(fn () => User::factory()->create([
			'name' => 'Test User',
			'email' => 'test@example.com',
		]));
	}
}

<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
	/**
	 * Возвращает состояние предмета по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$name = fake()->unique()->words(2, true);

		return [
			'name' => Str::title($name),
			'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
			'position' => fake()->numberBetween(1, 100),
			'seo_title' => fake()->optional()->sentence(),
			'seo_description' => fake()->optional()->sentence(),
			'seo_keywords' => fake()->optional()->words(5, true),
			'article' => fake()->optional()->paragraph(),
		];
	}
}


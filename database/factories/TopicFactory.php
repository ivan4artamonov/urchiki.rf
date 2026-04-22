<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
{
	/**
	 * Возвращает состояние темы по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$name = fake()->unique()->sentence(3);

		return [
			'subject_id' => Subject::factory(),
			'name' => $name,
			'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
			'position' => fake()->numberBetween(1, 100),
			'seo_title' => fake()->optional()->sentence(),
			'seo_description' => fake()->optional()->sentence(),
			'seo_keywords' => fake()->optional()->words(5, true),
			'article' => fake()->optional()->paragraph(),
		];
	}
}


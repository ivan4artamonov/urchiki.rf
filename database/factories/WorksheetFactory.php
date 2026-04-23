<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Quarter;
use App\Models\Topic;
use App\Models\Worksheet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Worksheet>
 */
class WorksheetFactory extends Factory
{
	/**
	 * Возвращает состояние рабочего листа по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$grade = Grade::query()->create([
			'number' => fake()->numberBetween(1, 11),
		]);
		$quarter = Quarter::query()->create([
			'grade_id' => $grade->id,
			'number' => fake()->numberBetween(1, 4),
		]);

		return [
			'topic_id' => Topic::factory(),
			'quarter_id' => $quarter->id,
			'title' => $title = fake()->sentence(4),
			'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
			'is_active' => fake()->boolean(80),
			'seo_title' => fake()->optional()->sentence(),
			'seo_description' => fake()->optional()->sentence(),
			'seo_keywords' => fake()->optional()->words(5, true),
			'article' => fake()->optional()->paragraph(),
		];
	}
}

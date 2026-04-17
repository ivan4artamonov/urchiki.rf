<?php

namespace Database\Factories;

use App\Models\FaqItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FaqItem>
 */
class FaqItemFactory extends Factory
{
	/**
	 * Возвращает состояние вопроса-ответа по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'question' => fake()->unique()->sentence(6),
			'answer' => fake()->paragraphs(2, true),
			'is_active' => true,
		];
	}

	/**
	 * Помечает запись как неактивную.
	 */
	public function inactive(): static
	{
		return $this->state(fn (array $attributes) => [
			'is_active' => false,
		]);
	}
}

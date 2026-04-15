<?php

namespace Database\Factories;

use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tariff>
 */
class TariffFactory extends Factory
{
	/**
	 * Возвращает состояние тарифа по умолчанию.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$durationDays = fake()->randomElement([1, 7, 30, 365]);
		$downloadsLimit = match ($durationDays) {
			1 => 5,
			7 => 20,
			30 => 60,
			365 => 500,
			default => fake()->numberBetween(10, 100),
		};
		$price = match ($durationDays) {
			1 => 79,
			7 => 249,
			30 => 499,
			365 => 2990,
			default => fake()->numberBetween(99, 4999),
		};

		return [
			'name' => fake()->unique()->words(2, true),
			'description' => fake()->optional(0.7)->sentence(),
			'duration_days' => $durationDays,
			'downloads_limit' => $downloadsLimit,
			'price' => $price,
			'is_active' => true,
			'is_featured' => false,
			'sort_order' => fake()->numberBetween(1, 100),
		];
	}

	/**
	 * Помечает тариф как неактивный.
	 */
	public function inactive(): static
	{
		return $this->state(fn (array $attributes) => [
			'is_active' => false,
		]);
	}

	/**
	 * Помечает тариф как акцентный.
	 */
	public function featured(): static
	{
		return $this->state(fn (array $attributes) => [
			'is_featured' => true,
		]);
	}
}

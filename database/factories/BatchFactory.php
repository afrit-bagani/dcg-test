<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
  public function definition(): array
  {
    // 1. Calculate realistic academic years
    $startYear = $this->faker->numberBetween(2018, 2024);
    $duration = $this->faker->randomElement([2, 3, 4, 5]); // 2 to 5 year degrees
    $endYear = $startYear + $duration;

    // Grab just the last two digits of the end year (e.g., 2026 -> 26)
    $shortEndYear = substr($endYear, -2);

    return [
      'code' => strtoupper($this->faker->unique()->lexify('???')) . '-' . $this->faker->unique()->numerify('####'),
      'name' => "{$startYear}-{$shortEndYear}", // Generates "2022-26"
      'is_active' => $this->faker->boolean(80), // 80% chance to be Active (1)
      'created_by' => $this->faker->numberBetween(1, 5), // Assigned to one of our 5 test users
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}

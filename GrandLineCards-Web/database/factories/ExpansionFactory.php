<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expansion>
 */
class ExpansionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'OP'.str_pad($this->faker->unique()->numberBetween(2, 99), 2, '0', STR_PAD_LEFT),
            'name' => $this->faker->words(3, true),
            'release_date' => $this->faker->date(),
        ];
    }
}

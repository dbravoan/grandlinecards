<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Support\Str;

class DeckFactory extends Factory
{
    protected $model = Deck::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->words(3, true),
            'user_id' => User::factory(),
            'leader_id' => $this->faker->bothify('OP##-###'),
            'is_public' => $this->faker->boolean(80),
        ];
    }
}

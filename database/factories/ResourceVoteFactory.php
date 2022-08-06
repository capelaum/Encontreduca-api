<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ResourceVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'resource_id' => $this->faker->numberBetween(1, Resource::count()),
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'vote' => $this->faker->boolean,
            'justification' => $this->faker->text
        ];
    }
}

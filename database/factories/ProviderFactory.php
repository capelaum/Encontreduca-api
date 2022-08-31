<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = collect(User::all()->modelKeys());

        return [
            'user_id' => $users->random(),
            'provider' => $this->faker->randomElement([
                'google',
                'github',
            ]),
            'provider_id' => $this->faker->randomNumber(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $createdAt = fake()->dateTimeThisYear();

        return [
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'resource_id' => $this->faker->numberBetween(1, Resource::count()),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text,
            'created_at' => $createdAt,
            'updated_at' => $createdAt->add(new \DateInterval('P10D'))
        ];
    }
}

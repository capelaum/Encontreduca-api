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
        $users = collect(User::all()->modelKeys());
        $resources = collect(Resource::all()->modelKeys());

        $createdAt = fake()->dateTimeThisYear();
        $updatedAt = $createdAt->add(new \DateInterval('P10D'));

        return [
            'user_id' => $users->random(),
            'resource_id' => $resources->random(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
    }
}

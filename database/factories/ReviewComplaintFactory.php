<?php

namespace Database\Factories;

use App\Models\Motive;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ReviewComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'review_id' => $this->faker->numberBetween(1, Review::count()),
            'motive_id' => $this->faker->numberBetween(1, Motive::count()),
        ];
    }
}

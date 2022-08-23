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
        $users = collect(User::all()->modelKeys());
        $reviews = collect(Review::all()->modelKeys());
        $motives = collect(Motive::all()->modelKeys());

        return [
            'user_id' => $users->random(),
            'review_id' => $reviews->random(),
            'motive_id' => $motives->random(),
        ];
    }
}

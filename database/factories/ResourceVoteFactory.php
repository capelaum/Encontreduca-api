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
        $users = collect(User::all()->modelKeys());
        $resources = collect(Resource::all()->modelKeys());

        return [
            'user_id' => $users->random(),
            'resource_id' => $resources->random(),
            'vote' => $this->faker->boolean,
            'justification' => $this->faker->text
        ];
    }
}

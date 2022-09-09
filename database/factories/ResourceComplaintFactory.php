<?php

namespace Database\Factories;

use App\Models\Motive;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ResourceComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = collect(User::all()->modelKeys());
        $resources = collect(Resource::all()->modelKeys());
        $motives = collect(Motive::where('type', 'resource_complaint')
            ->get()
            ->modelKeys());

        return [
            'user_id' => $users->random(),
            'resource_id' => $resources->random(),
            'motive_id' => $motives->random(),
        ];
    }
}

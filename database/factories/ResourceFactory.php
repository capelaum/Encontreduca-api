<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = collect(User::all()->modelKeys());
        $categories = collect(Category::all()->modelKeys());

        $createdAt = fake()->dateTimeThisYear();
        $updatedAt = $createdAt->add(new \DateInterval('P10D'));

        return [
            'name' => $this->faker->name,
            'category_id' => $categories->random(),
            'user_id' => $users->random(),
            'latitude' => $this->faker->randomFloat(6, -15.70, -15.95),
            'longitude' => $this->faker->randomFloat(6, -47.75, -48.15),
            'address' => $this->faker->address,
            'website' => $this->faker->url,
            'phone' => $this->faker->phoneNumber,
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => $this->faker->boolean(50),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
    }
}

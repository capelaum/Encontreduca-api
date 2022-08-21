<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
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
        $createdAt = fake()->dateTimeThisYear();

        return [
            'name' => $this->faker->name,
            'category_id' => $this->faker->numberBetween(1, Category::count()),
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'latitude' => $this->faker->randomFloat(6, -15.70, -15.95),
            'longitude' => $this->faker->randomFloat(6, -47.75, -48.15),
            'address' => $this->faker->address,
            'website' => $this->faker->url,
            'phone' => $this->faker->phoneNumber,
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => $this->faker->boolean(50),
            'created_at' => $createdAt,
            'updated_at' => $createdAt->add(new \DateInterval('P10D'))
        ];
    }
}

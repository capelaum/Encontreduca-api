<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResourceChange>
 */
class ResourceChangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $resourceChanges = [
            [
                'field' => 'name',
                'old_value' => 'Old Name',
                'new_value' => 'New Name'
            ],
            [
                'field' => 'address',
                'old_value' => 'Old Address',
                'new_value' => 'New Address'
            ],
            [
                'field' => 'website',
                'old_value' => 'Old website',
                'new_value' => 'New website'
            ],
            [
                'field' => 'phone',
                'old_value' => 'Old phone',
                'new_value' => 'New phone'
            ],
            [
                'field' => 'cover',
                'old_value' => $this->faker->imageUrl(),
                'new_value' => $this->faker->imageUrl(),
            ],
            [
                'field' => 'latitude',
                'old_value' => $this->faker->latitude,
                'new_value' => $this->faker->latitude,
            ],
            [
                'field' => 'longitude',
                'old_value' => $this->faker->longitude,
                'new_value' => $this->faker->longitude,
            ],
        ];

        $randomResourceChange = $this->faker->randomElement($resourceChanges);

        return [
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'resource_id' => $this->faker->numberBetween(1, Resource::count()),
            'category_id' => $this->faker->numberBetween(1, Category::count()),
            'field' => $randomResourceChange['field'],
            'old_value' => $randomResourceChange['old_value'],
            'new_value' => $randomResourceChange['new_value'],
        ];
    }
}

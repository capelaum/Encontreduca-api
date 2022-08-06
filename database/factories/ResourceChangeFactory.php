<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
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
        $randomResource = Resource::find($this->faker->numberBetween(1, Resource::count()));

        $resourceChanges = [
            [
                'field' => 'name',
                'old_value' => $randomResource->name,
                'new_value' => 'New Name'
            ],
            [
                'field' => 'address',
                'old_value' => $randomResource->address,
                'new_value' => 'New Address'
            ],
            [
                'field' => 'category_id',
                'old_value' => $randomResource->category_id,
                'new_value' => $this->faker->numberBetween(1, Category::count())
            ],
            [
                'field' => 'website',
                'old_value' => $randomResource->website,
                'new_value' => 'New website'
            ],
            [
                'field' => 'phone',
                'old_value' => $randomResource->phone,
                'new_value' => 'New phone'
            ],
            [
                'field' => 'cover',
                'old_value' => $randomResource->cover,
                'new_value' => $this->faker->imageUrl(),
            ],
            [
                'field' => 'latitude',
                'old_value' => $randomResource->latitude,
                'new_value' => $this->faker->latitude,
            ],
            [
                'field' => 'longitude',
                'old_value' => $randomResource->longitude,
                'new_value' => $this->faker->longitude,
            ],
        ];

        $randomResourceChange = $this->faker->randomElement($resourceChanges);

        return [
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'resource_id' => $randomResource->id,
            'field' => $randomResourceChange['field'],
            'old_value' => $randomResourceChange['old_value'],
            'new_value' => $randomResourceChange['new_value'],
        ];
    }
}

<?php

namespace Database\Factories;

use App\Http\Resources\V1\EducationalResource\ResourceResource;
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
    public function definition(): array
    {
        $randomResource = new ResourceResource(Resource::inRandomOrder()->first());

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
                'field' => 'position',
                'old_value' => $randomResource->latitude . ',' . $randomResource->longitude,
                'new_value' => $this->faker->latitude() . ',' . $this->faker->longitude(),
            ],
        ];

        $randomResourceChange = $this->faker->randomElement($resourceChanges);

        $users = collect(User::all()->modelKeys());

        return [
            'user_id' => $users->random(),
            'resource_id' => $randomResource->id,
            'field' => $randomResourceChange['field'],
            'old_value' => $randomResourceChange['old_value'],
            'new_value' => $randomResourceChange['new_value'],
        ];
    }
}

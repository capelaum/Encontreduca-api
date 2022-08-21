<?php

namespace Tests\Feature\Api\V1;

use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function resourceResponseArray(Resource $resource): array
    {
        return [
            'id' => $resource->id,
            'userId' => $resource->user_id,
            'author' => $resource->user->name,
            'categoryId' => $resource->category_id,
            'categoryName' => $resource->category->name,
            'name' => $resource->name,
            'address' => $resource->address,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
            'website' => $resource->website,
            'phone' => $resource->phone,
            'cover' => $resource->cover,
            'approved' => $resource->approved,
            'createdAt' => date('d/m/Y', strtotime($resource->created_at)),
            'updatedAt' => date('d/m/Y', strtotime($resource->updated_at)),
        ];
    }

    public function resourceArray(Resource $resource): array
    {
        return [
            'user_id' => $resource->user_id,
            'category_id' => $resource->category_id,
            'name' => $resource->name,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
            'address' => $resource->address,
            'website' => $resource->website,
            'phone' => $resource->phone,
            'cover' => $resource->cover,
            'approved' => $resource->approved,
        ];
    }

    public function test_list_all_resources()
    {
        $resource = $this->createResource();
        $resource_2 = $this->createResource();

        $response = $this->getJson(route('resources.index'))
            ->assertOk();

        $response->assertJson([
            $this->resourceResponseArray($resource),
            $this->resourceResponseArray($resource_2),
        ]);

        $this->assertDatabaseHas('resources', $this->resourceArray($resource));
    }
}

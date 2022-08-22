<?php

namespace Tests\Feature\Api\V1;

use App\Http\Resources\V1\EducationalResource\ResourceCollection;
use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private mixed $resource;

    public function setup(): void
    {
        parent::setup();

        $this->authUser();

        $this->resource = $this->createResource();
    }

    public function test_list_all_resources()
    {
        $response = $this->getJson(route('resources.index'))
            ->assertOk();

        $resourcesResponse = (new ResourceCollection([$this->resource]))->toArray($this->resource);

        $response->assertJson($resourcesResponse);
    }

    public function test_show_resource()
    {
        $response = $this->getJson(route('resources.show', $this->resource->id))
            ->assertOk();

        $resourceResponse = (new ResourceResource($this->resource))->toArray($this->resource);

        $this->assertEquals($response->json(), $resourceResponse);
    }

    public function test_get_resource_reviews()
    {
        $this->createReviews(3, [
            'resource_id' => $this->resource->id
        ]);

        $response = $this->getJson(route('resources.reviews', $this->resource->id))
            ->assertOk()
            ->json();

        $resourceReviews = (new ReviewCollection($this->resource->reviews))
            ->toArray($this->resource->reviews);

        $this->assertEquals($response, $resourceReviews);
    }

    public function test_get_resource_votes()
    {
        $this->createResourceVotes(3, [
            'resource_id' => $this->resource->id
        ]);

        $response = $this->getJson(route('resources.votes', $this->resource->id))
            ->assertOk()
            ->json();

        $resourceVotes = (new ResourceVoteCollection($this->resource->votes))
            ->toArray($this->resource->votes);

        $this->assertEquals($response, $resourceVotes);
    }
}

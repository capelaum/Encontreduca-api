<?php

namespace Tests\Feature\Api\V1;

use App\Http\Resources\V1\EducationalResource\ResourceCollection;
use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    private mixed $resource;
    private array $resourceKeys = [
        'id',
        'userId',
        'author',
        'categoryId',
        'categoryName',
        'name',
        'address',
        'latitude',
        'longitude',
        'website',
        'phone',
        'cover',
        'approved',
        'createdAt',
        'updatedAt'
    ];

    public function setup(): void
    {
        parent::setup();

        $this->authUser();

        $this->resource = $this->createResource();
    }

    public function test_list_all_resources()
    {
        $this->getJson(route('resources.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceKeys]);
    }

    public function test_show_resource()
    {
        $response = $this->getJson(route('resources.show', $this->resource->id))
            ->assertOk();

        $resourceResponse = (new ResourceResource($this->resource))->toArray($this->resource);

        $this->assertEquals($response->json(), $resourceResponse);
    }

    public function test_store_resource()
    {
        $response = $this->postJson(route('resources.store'), [
            'userId' => Auth::user()->id,
            'categoryId' => Category::all()->random()->id,
            'position' => [
                'lat' => -15.7810843,
                'lng' => -47.8928717
            ],
            'name' => 'Colégio Militar de Brasília',
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'phone' => '(61) 3424-1128',
            'website' => 'http://www.cmb.eb.mil.br',
            'cover' => 'https://dummyimage.com/380x200/333/fff'
        ])->assertCreated()->assertJsonStructure($this->resourceKeys)->json();

        $this->assertDatabaseHas('resources', [
            'id' => $response['id'],
            'name' => $response['name'],
        ]);
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

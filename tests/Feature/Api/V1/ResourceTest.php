<?php

namespace Tests\Feature\Api\V1;

use App\Http\Resources\V1\EducationalResource\ResourceCollection;
use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
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
        ])->assertCreated()->assertJsonStructure([
           'id',
           'user_id',
           'category_id',
           'name',
           'address',
           'phone',
           'website',
           'cover',
           'latitude',
           'longitude',
           'updated_at',
           'created_at',
        ])->json();

        $this->assertDatabaseHas('resources',[
            'id' =>  $response['id'],
            'name' => $response['name'],
            'address' => $response['address'],
            'cover' => $response['cover'],
            'latitude' => $response['latitude'],
            'longitude' => $response['longitude'],
            'phone' => $response['phone'],
            'website' => $response['website'],
            'category_id' => $response['category_id']
        ]);
    }
}

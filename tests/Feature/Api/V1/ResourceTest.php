<?php

namespace Tests\Feature\Api\V1;

use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    private string $coverUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/covers/pslj3lojhuzklk8fb9p6.jpg";

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
        $cover = $this->createFakeImageFile('cover.jpg');

        $cloudinaryFolder = config('app.cloudinary_folder');

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($cover->getRealPath(), [
                'folder' => "$cloudinaryFolder/covers",
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->coverUrl);

        $response = $this->postJson(route('resources.store'), [
            'userId' => Auth::user()->id,
            'categoryId' => Category::all()->random()->id,
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'name' => 'Colégio Militar de Brasília',
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'phone' => '(61) 3424-1128',
            'website' => 'http://www.cmb.eb.mil.br',
            'cover' => $cover
        ])
            ->assertCreated()
            ->assertJsonStructure($this->resourceKeys)
            ->json();

        $this->assertDatabaseHas('resources', [
            'id' => $response['id'],
            'name' => $response['name'],
            'cover' => $this->coverUrl
        ]);
    }

    public function test_get_resource_reviews()
    {
        $this->createReview(['resource_id' => $this->resource->id]);

        $response = $this->getJson(route('resources.reviews', $this->resource->id))
            ->assertOk()
            ->json();

        $resourceReviews = (new ReviewCollection($this->resource->reviews))
            ->toArray($this->resource->reviews);

        $this->assertEquals($response, $resourceReviews);
    }
}

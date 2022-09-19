<?php

namespace Tests\Feature\Admin;

use App\Http\Resources\Admin\ResourceResource;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminResourceTest extends TestCase
{
    use RefreshDatabase;

    private string $coverUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/covers/pslj3lojhuzklk8fb9p6.jpg";

    private string $cloudinaryFolder;

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

        $this->authAdmin();

        $this->cloudinaryFolder = config('app.cloudinary_folder');

        $this->resource = $this->createResource();
    }

    public function test_admin_list_resources()
    {
        $this->getJson(route('admin.resources.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->resourceKeys
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_admin_show_resource()
    {
        $response = $this->getJson(route('admin.resources.show', $this->resource->id))
            ->assertOk();

        $resourceResponse = (new ResourceResource($this->resource))->toArray($this->resource);

        $this->assertEquals($response->json(), $resourceResponse);
    }

    public function test_admin_store_resource()
    {
        $cover = $this->createFakeImageFile('cover.jpg');

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($cover->getRealPath(), [
                'folder' => "$this->cloudinaryFolder/covers",
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->coverUrl);

        $response = $this->postJson(route('admin.resources.store'), [
            'userId' => Auth::user()->id,
            'categoryId' => Category::all()->random()->id,
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'name' => 'Colégio Militar de Brasília',
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'phone' => '(61) 3424-1128',
            'website' => 'http://www.cmb.eb.mil.br',
            'cover' => $cover,
            'approved' => true,
        ])
            ->assertCreated()
            ->assertJsonStructure($this->resourceKeys)
            ->json();

        $this->assertDatabaseHas('resources', [
            'id' => $response['id'],
            'name' => $response['name'],
            'cover' => $this->coverUrl,
            'approved' => true,
        ]);
    }

    public function test_user_cannot_admin_store_resource()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $response = $this->postJson(route('admin.resources.store'), [
            'userId' => Auth::user()->id,
            'categoryId' => Category::all()->random()->id,
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'name' => 'Colégio Militar de Brasília',
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'phone' => '(61) 3424-1128',
            'website' => 'http://www.cmb.eb.mil.br',
            'cover' => null,
            'approved' => true,
        ])->assertUnauthorized();
    }

    public function test_admin_update_resource()
    {
        $cover = $this->createFakeImageFile('cover.jpg');

        $coverUrlArray = explode('/', $this->coverUrl);
        $publicId = explode('.', end($coverUrlArray))[0];

        $resource = $this->createResource([
            'cover' => $this->coverUrl
        ]);

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($cover->getRealPath(), [
                'folder' => "$this->cloudinaryFolder/covers",
                'public_id' => $publicId
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->coverUrl);

        $response = $this->putJson(route('admin.resources.update', $resource->id), [
            'categoryId' => Category::all()->random()->id,
            'name' => 'Colégio Militar de Brasília',
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'website' => 'http://www.cmb.eb.mil.br',
            'phone' => '(61) 3424-1128',
            'cover' => $cover,
            'approved' => true,
        ])
            ->assertOk()
            ->assertJsonStructure($this->resourceKeys)
            ->json();

        $this->assertDatabaseHas('resources', [
            'id' => $response['id'],
            'name' => $response['name'],
            'cover' => $this->coverUrl,
            'approved' => true,
        ]);
    }

    public function test_admin_update_resource_with_patch()
    {
        $resource = $this->createResource();

        $response = $this->patchJson(route('admin.resources.update', $resource->id), [
            'categoryId' => Category::all()->random()->id,
            'name' => 'Colégio Militar de Brasília',
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'website' => 'http://www.cmb.eb.mil.br',
            'phone' => '(61) 3424-1128',
            'approved' => false,
        ])
            ->assertOk()
            ->assertJsonStructure($this->resourceKeys)
            ->json();

        $this->assertDatabaseHas('resources', [
            'id' => $response['id'],
            'name' => $response['name'],
            'category_id' => $response['categoryId'],
            'approved' => false,
        ]);
    }

    public function test_user_cannot_admin_update_resource()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $cover = $this->createFakeImageFile('cover.jpg');

        $this->putJson(route('admin.resources.update', $this->resource->id), [
            'userId' => Auth::user()->id,
            'categoryId' => Category::all()->random()->id,
            'latitude' => -15.7810843,
            'longitude' => -47.8928717,
            'name' => 'Colégio Militar de Brasília',
            'address' => '902/904 - Asa Norte, Brasília - DF, 70790-020',
            'phone' => '(61) 3424-1128',
            'website' => 'http://www.cmb.eb.mil.br',
            'cover' => $cover,
            'approved' => true,
        ])->assertUnauthorized();
    }

    public function test_admin_delete_resource()
    {
        $resource = $this->createResource([
            'cover' => $this->coverUrl
        ]);

        $coverUrlArray = explode('/', $this->coverUrl);
        $publicId = explode('.', end($coverUrlArray))[0];

        Cloudinary::shouldReceive('destroy')
            ->once()
            ->with("$this->cloudinaryFolder/covers/$publicId")
            ->andReturnSelf();

        $this->deleteJson(route('admin.resources.destroy', $resource->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('resources', [
            'id' => $resource->id
        ]);
    }

    public function test_user_cannot_admin_delete_resource()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->deleteJson(route('admin.resources.destroy', $this->resource->id))
            ->assertUnauthorized();
    }

    public function test_admin_list_resource_votes()
    {
        $this->createResourceVote([
            'resource_id' => $this->resource->id,
            'user_id' => auth()->user()->id
        ]);

        $this->getJson(route('admin.resources.votes', [
            'resource' => $this->resource->id,
            'search' => 'name'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'userId',
                        'resourceId',
                        'author',
                        'authorEmail',
                        'authorAvatar',
                        'vote',
                        'justification'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_admin_list_resource_reviews()
    {
        $this->createReview([
            'resource_id' => $this->resource->id,
            'user_id' => auth()->user()->id
        ]);

        $this->getJson(route('admin.resources.reviews', [
            'resource' => $this->resource->id,
            'search' => 'name'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'userId',
                        'resourceId',
                        'author',
                        'authorEmail',
                        'authorAvatar',
                        'rating',
                        'comment',
                        'updatedAt'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_admin_list_resource_complaints()
    {
        $this->createResourceComplaint([
            'resource_id' => $this->resource->id,
            'user_id' => auth()->user()->id
        ]);

        $this->getJson(route('admin.resources.complaints', [
            'resource' => $this->resource->id,
            'search' => 'name'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'userId',
                        'resourceId',
                        'resourceName',
                        'author',
                        'authorEmail',
                        'authorAvatar',
                        'motiveId',
                        'motiveName',
                        'createdAt'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_admin_list_resource_changes()
    {
        $this->createResourceChange([
            'resource_id' => $this->resource->id,
            'user_id' => auth()->user()->id
        ]);

        $this->getJson(route('admin.resources.changes', [
            'resource' => $this->resource->id,
            'search' => 'name'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'userId',
                        'resourceId',
                        'resourceName',
                        'author',
                        'authorEmail',
                        'authorAvatar',
                        'field',
                        'oldValue',
                        'newValue',
                        'createdAt'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }
}

<?php

namespace Tests\Feature\Api\V1;

use App\Models\ResourceChange;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceChangeTest extends TestCase
{
    use RefreshDatabase;

    private string $coverUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/avatars/pslj3lojhuzklk8fb9p6.jpg";

    private array $resourceChangeKeys = [
        'id',
        'userId',
        'resourceId',
        'field',
        'oldValue',
        'newValue',
        'createdAt',
    ];

    public function test_list_resources_changes()
    {
        $this->authAdmin();

        $this->createResourceChange();

        $this->getJson(route('resources.changes.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceChangeKeys]);
    }

    public function test_user_cannot_list_resources_changes()
    {
        $this->authUser();

        $this->createResourceChange();

        $this->withExceptionHandling();

        $this->getJson(route('resources.changes.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_resource_change()
    {
        $this->authAdmin();

        $resourceChange = $this->createResourceChange();

        $this->getJson(route('resources.changes.show', $resourceChange->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceChangeKeys)
            ->json();
    }

    public function test_user_cannot_show_resource_change()
    {
        $this->authUser();

        $resourceChange = $this->createResourceChange();

        $this->withExceptionHandling();

        $this->getJson(route('resources.changes.show', $resourceChange->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_store_resource_change()
    {
        $this->authUser();

        $resource = $this->createResource();
        $resourceChange = ResourceChange::factory()->make();

        $this->postJson(route('resources.changes.store', [
            'resourceId' => $resource->id,
            'field' => $resourceChange->field,
            'oldValue' => $resourceChange->old_value,
            'newValue' => $resourceChange->new_value,
        ]))
            ->assertCreated()
            ->assertJsonStructure($this->resourceChangeKeys)
            ->json();

        $this->assertDatabaseHas('resource_changes', [
            'resource_id' => $resource->id,
            'field' => $resourceChange->field,
            'old_value' => $resourceChange->old_value,
            'new_value' => $resourceChange->new_value,
        ]);
    }

    public function test_store_resource_change_with_cover()
    {
        $this->authUser();

        $resource = $this->createResource();
        $resourceChange = ResourceChange::factory([
            'resource_id' => $resource->id,
            'field' => 'cover',
            'old_value' => 'https://dummyimage.com/380x200/333/fff',
            'new_value' => 'newCover',
        ])->make();

        $cover = $this->createFakeImageFile('cover.jpg');

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($cover->getRealPath(), [
                'folder' => 'encontreduca/covers/changes',
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->coverUrl);

        $this->postJson(route('resources.changes.store'), [
            'resourceId' => $resource->id,
            'field' => $resourceChange->field,
            'oldValue' => $resourceChange->old_value,
            'newValue' => $resourceChange->new_value,
            'cover' => $cover,
        ])
            ->assertCreated()
            ->assertJsonStructure($this->resourceChangeKeys);

        $this->assertDatabaseHas('resource_changes', [
            'resource_id' => $resource->id,
            'field' => $resourceChange->field,
            'old_value' => $resourceChange->old_value,
            'new_value' => $this->coverUrl,
        ]);
    }
}

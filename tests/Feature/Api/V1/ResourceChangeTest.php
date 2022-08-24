<?php

namespace Tests\Feature\Api\V1;

use App\Models\ResourceChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceChangeTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_store_review_complaint()
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
}

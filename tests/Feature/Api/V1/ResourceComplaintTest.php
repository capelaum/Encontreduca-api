<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceComplaintTest extends TestCase
{
    use RefreshDatabase;

    private array $resourceComplaintKeys = [
        'id',
        'userId',
        'resourceId',
        'motiveId',
        'createdAt',
    ];

    public function test_store_resource_complaint()
    {
        $this->authUser();

        $resource = $this->createResource();
        $motive = $this->createMotive();

        $this->postJson(route('resources.complaints.store', [
            'resourceId' => $resource->id,
            'motiveId' => $motive->id,
        ]))
            ->assertCreated()
            ->assertJsonStructure($this->resourceComplaintKeys)->json();

        $this->assertDatabaseHas('resource_complaints', [
            'resource_id' => $resource->id,
            'motive_id' => $motive->id,
        ]);

    }
}

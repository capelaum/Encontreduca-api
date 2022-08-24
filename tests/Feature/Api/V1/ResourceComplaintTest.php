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

    public function test_list_resources_complaints()
    {
        $this->authAdmin();

        $this->createResourceComplaint();

        $this->getJson(route('resources.complaints.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceComplaintKeys])->json();
    }

    public function test_user_cannot_list_reviews_complaints()
    {
        $this->authUser();

        $this->createResourceComplaint();

        $this->withExceptionHandling();

        $this->getJson(route('resources.complaints.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_review_complaint()
    {
        $this->authAdmin();

        $resourceComplaint = $this->createResourceComplaint();

        $this->getJson(route('resources.complaints.show', $resourceComplaint->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceComplaintKeys)->json();
    }

    public function test_user_cannot_show_resource_complaint()
    {
        $this->authUser();

        $resourceComplaint = $this->createResourceComplaint();

        $this->withExceptionHandling();

        $this->getJson(route('resources.complaints.show', $resourceComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

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

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

    public function test_list_reviews_complaints()
    {
        $this->authAdmin();

        $this->createResourceComplaint();

        $response = $this->getJson(route('resources.complaints.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceComplaintKeys])->json();
    }
}

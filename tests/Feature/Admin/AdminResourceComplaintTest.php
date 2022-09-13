<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminResourceComplaintTest extends TestCase
{
    use RefreshDatabase;

    private mixed $resourceComplaint;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();

        $this->resourceComplaint = $this->createResourceComplaint();
    }

    private array $resourceComplaintKeys = [
        'id',
        'userId',
        'resourceId',
        'resourceName',
        'motiveId',
        'motiveName',
        'createdAt',
    ];

    public function test_admin_list_resources_complaints()
    {
        $this->getJson(route('admin.resources.complaints.index', [
            'search' => 'name',
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->resourceComplaintKeys
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

    public function test_user_cannot_list_reviews_complaints()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.resources.complaints.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_review_complaint()
    {
        $this->getJson(route('admin.resources.complaints.show', $this->resourceComplaint->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceComplaintKeys)->json();
    }

    public function test_user_cannot_show_resource_complaint()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.resources.complaints.show', $this->resourceComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_delete_resource_complaint()
    {
        $this->deleteJson(route('admin.resources.complaints.destroy', $this->resourceComplaint->id))
            ->assertNoContent();
    }

    public function test_user_cannot_delete_resource_complaint()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->deleteJson(route('admin.resources.complaints.destroy', $this->resourceComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

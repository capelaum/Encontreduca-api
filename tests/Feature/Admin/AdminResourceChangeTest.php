<?php

namespace Tests\Feature\Admin;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminResourceChangeTest extends TestCase
{
    use RefreshDatabase;

    private string $coverUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/covers/pslj3lojhuzklk8fb9p6.jpg";

    private array $resourceChangeKeys = [
        'id',
        'userId',
        'author',
        'authorEmail',
        'authorAvatar',
        'resourceId',
        'resourceName',
        'field',
        'oldValue',
        'newValue',
        'createdAt',
    ];

    public function test_admin_list_resources_changes()
    {
        $this->authAdmin();

        $this->createResourceChange();

        $this->getJson(route('admin.resources.changes.index', [
            'search' => 'name',
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->resourceChangeKeys
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

    public function test_user_cannot_admin_list_resources_changes()
    {
        $this->authUser();

        $this->createResourceChange();

        $this->withExceptionHandling();

        $this->getJson(route('admin.resources.changes.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_show_resource_change()
    {
        $this->authAdmin();

        $resourceChange = $this->createResourceChange();

        $this->getJson(route('admin.resources.changes.show', $resourceChange->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceChangeKeys)
            ->json();
    }

    public function test_user_cannot_admin_show_resource_change()
    {
        $this->authUser();

        $resourceChange = $this->createResourceChange();

        $this->withExceptionHandling();

        $this->getJson(route('admin.resources.changes.show', $resourceChange->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_delete_resource_change()
    {
        $this->authAdmin();

        $resourceChange = $this->createResourceChange([
            'field' => 'cover',
            'old_value' => $this->coverUrl,
            'new_value' => $this->coverUrl,
        ]);

        $cloudinaryFolder = config('app.cloudinary_folder');

        $coverUrlArray = explode('/', $resourceChange->new_value);
        $publicId = explode('.', end($coverUrlArray))[0];

        Cloudinary::shouldReceive('destroy')
            ->once()
            ->with("$cloudinaryFolder/covers/changes/$publicId")
            ->andReturnSelf();

        $this->deleteJson(route('admin.resources.changes.destroy', $resourceChange->id))
            ->assertNoContent();
    }

    public function test_user_cannot_admin_delete_resource_change()
    {
        $this->authUser();

        $resourceChange = $this->createResourceChange();

        $this->withExceptionHandling();

        $this->deleteJson(route('admin.resources.changes.destroy', $resourceChange->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

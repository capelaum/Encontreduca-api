<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminSupportTest extends TestCase
{
    use RefreshDatabase;

    private mixed $support;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();

        $this->support = $this->createSupport();
    }

    private array $supportKeys = [
        'id',
        'userId',
        'author',
        'authorEmail',
        'authorAvatar',
        'message',
        'createdAt'
    ];

    public function test_admin_list_supports()
    {
        $this->getJson(route('admin.supports.index', [
            'search' => 'test'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->supportKeys
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

    public function test_user_cannot_admin_list_supports()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.supports.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_show_support()
    {
        $this->getJson(route('admin.supports.show', $this->support->id))
            ->assertOk()
            ->assertJsonStructure($this->supportKeys)->json();
    }

    public function test_user_cannot_admin_show_support()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.supports.show', $this->support->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_delete_support()
    {
        $this->deleteJson(route('admin.supports.destroy', $this->support->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('supports', [
            'id' => $this->support->id
        ]);
    }

    public function test_user_cannot_admin_delete_support()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->deleteJson(route('admin.supports.destroy', $this->support->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

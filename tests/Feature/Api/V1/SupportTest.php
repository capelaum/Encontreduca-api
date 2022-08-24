<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase;

    private array $supportKeys = [
        'id',
        'userId',
        'message',
        'createdAt'
    ];

    public function test_list_supports()
    {
        $this->authAdmin();

        $this->createSupport();

        $this->getJson(route('supports.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->supportKeys])->json();
    }

    public function test_user_cannot_list_supports()
    {
        $this->authUser();

        $this->createSupport();

        $this->withExceptionHandling();

        //assert that it throws AuthorizationException
        $this->getJson(route('supports.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_support()
    {
        $this->authAdmin();

        $support = $this->createSupport();

        $this->getJson(route('supports.show', $support->id))
            ->assertOk()
            ->assertJsonStructure($this->supportKeys)->json();
    }

    public function test_user_cannot_show_support()
    {
        $this->authUser();

        $support = $this->createSupport();

        $this->withExceptionHandling();

        $this->getJson(route('supports.show', $support->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_store_support()
    {
        $this->authAdmin();

        $response = $this->postJson(route('supports.store'), [
            'userId' => Auth::id(),
            'message' => 'test'
        ])->assertCreated()
            ->assertJsonStructure($this->supportKeys)->json();

        $this->assertDatabaseHas('supports', [
            'id' => $response['id'],
            'message' => $response['message'],
        ]);
    }
}

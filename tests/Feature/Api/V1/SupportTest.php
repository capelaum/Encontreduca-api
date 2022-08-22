<?php

namespace Tests\Feature\Api\V1;

use App\Http\Resources\V1\SupportCollection;
use App\Http\Resources\V1\SupportResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_supports()
    {
        $this->authAdmin();

        $this->createSupport();

        $this->getJson(route('supports.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'message',
                    'createdAt'
                ]
            ])->json();
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
            ->assertJsonStructure([
                'id',
                'userId',
                'message',
                'createdAt'
            ])->json();
    }

    public function test_user_cannot_show_support()
    {
        $this->authUser();

        $support = $this->createSupport();

        $this->withExceptionHandling();

        //assert that it throws AuthorizationException
        $this->getJson(route('supports.show', $support->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_store_support()
    {
        $this->authAdmin();

        $this->postJson(route('supports.store'), [
            'userId' => Auth::user()->id,
            'message' => 'test'
        ])->assertOk()
            ->assertJsonStructure([
                'id',
                'user_id',
                'message'
            ])->json();
    }
}

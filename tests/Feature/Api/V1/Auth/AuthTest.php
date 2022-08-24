<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private array $userKeys = [
        'id',
        'name',
        'avatarUrl',
        'reviewCount',
        'resourcesIds'
    ];

    public function test_get_auth_user()
    {
        $this->authUser();

        $user = Auth::user();
        $user->reviewCount = $user->reviews()->count();
        $user->resourcesIds = $user->resources()->pluck('id')->toArray();

        $this->getJson(route('auth.user'))
            ->assertOk()
            ->assertJsonStructure($this->userKeys)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'avatarUrl' => $user->avatar_url,
                'reviewCount' => $user->reviewCount,
                'resourcesIds' => $user->resourcesIds
            ]);

    }

//    public function test_register()
//    {
//
//    }
}

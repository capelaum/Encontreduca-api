<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResourceUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_resource_user()
    {
        $this->authUser();

        $resource = $this->createResource();

        $this->postJson(route('resource.user.store', $resource->id))
            ->assertCreated()
            ->assertJsonStructure([
                'user_id',
                'resource_id'
            ]);

        $this->assertDatabaseHas('resource_user', [
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);
    }

    public function test_user_cannot_save_a_resource_twice()
    {
        $this->authUser();

        $resource = $this->createResource();
        $this->createResourceUser([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $this->postJson(route('resource.user.store', $resource->id))
            ->assertStatus(409)
            ->assertJson([
                'message' => 'Você já salvou este recurso.'
            ]);
    }

    public function test_delete_resource_user()
    {
        $this->authUser();

        $resource = $this->createResource();
        $this->createResourceUser([
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);

        $this->deleteJson(route('resource.user.destroy', $resource->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('resource_user', [
            'user_id' => Auth::id(),
            'resource_id' => $resource->id
        ]);
    }

    public function test_user_cannot_delete_saved_resource_of_another_user()
    {
        $this->withExceptionHandling();
        $this->authUser();

        $resource = $this->createResource();
        $this->createResourceUser([
            'user_id' => $this->userIdsWithoutAuthUser->random(),
            'resource_id' => $resource->id
        ]);

        $this->deleteJson(route('resource.user.destroy', $resource->id))
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Você não possui esse recurso salvo.'
            ]);
    }
}

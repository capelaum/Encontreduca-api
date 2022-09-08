<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminResourceVoteTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    private array $resourceVoteKeys = [
        'id',
        'userId',
        'resourceId',
        'author',
        'authorEmail',
        'authorAvatar',
        'vote',
        'justification'
    ];

    public function test_admin_list_resources_votes()
    {
        $this->createResourceVote();

        $this->getJson(route('admin.votes.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceVoteKeys]);
    }

    public function test_user_cannot_admin_list_resources_votes()
    {
        $this->authUser();

        $this->createResourceVote();

        $this->withExceptionHandling();

        $this->getJson(route('admin.votes.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_show_resources_vote()
    {
        $this->authAdmin();

        $resourceVote = $this->createResourceVote();

        $this->getJson(route('admin.votes.show', $resourceVote->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceVoteKeys)->json();
    }

    public function test_user_cannot_admin_show_resource_vote()
    {
        $this->authUser();

        $resourceVote = $this->createResourceVote();

        $this->withExceptionHandling();

        $this->getJson(route('admin.votes.show', $resourceVote->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_update_admin_resource_vote()
    {
        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->patchJson(route('admin.votes.update', $resourceVote->id), [
            'vote' => true,
            'justification' => 'New justification'
        ])->assertOk()
            ->assertJsonStructure($this->resourceVoteKeys);

        $this->assertDatabaseHas('resource_votes', [
            'id' => $resourceVote->id,
            'vote' => true,
            'justification' => 'New justification'
        ]);
    }

    public function test_user_cannot_update_admin_resource_vote()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->patchJson(route('admin.votes.update', $resourceVote->id), [
            'vote' => true,
            'justification' => 'New justification'
        ])->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_delete_admin_resource_vote()
    {
        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->deleteJson(route('admin.votes.destroy', $resourceVote->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('resource_votes', [
            'id' => $resourceVote->id
        ]);
    }

    public function test_user_cannot_delete_admin_resource_vote()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->deleteJson(route('admin.votes.destroy', $resourceVote->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

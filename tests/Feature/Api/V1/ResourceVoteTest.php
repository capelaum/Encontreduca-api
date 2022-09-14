<?php

namespace Tests\Feature\Api\V1;

use App\Models\ResourceVote;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ResourceVoteTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authUser();
    }

    private array $resourceVoteKeys = [
        'id',
        'userId',
        'resourceId',
        'vote',
        'justification'
    ];

    public function test_store_resource_vote()
    {
        $resource = $this->createResource();
        $resourceVote = ResourceVote::factory()->make();

        $data = [
            'resourceId' => $resource->id,
            'vote' => $resourceVote->vote,
            'justification' => $resourceVote->justification,
        ];

        $this->postJson(route('resources.votes.store'), $data)
            ->assertCreated()
            ->assertJsonStructure($this->resourceVoteKeys);
    }

    public function test_user_cannot_create_two_votes_on_same_resource()
    {
        $resource = $this->createResource();

        $resourceVote = $this->createResourceVote([
            'user_id' => Auth::user()->id,
            'resource_id' => $resource->id
        ]);

        $this->postJson(route('resources.votes.store'), [
            'resourceId' => $resource->id,
            'vote' => $resourceVote->vote,
            'justification' => $resourceVote->justification
        ])->assertStatus(409)
            ->assertJson([
                'message' => 'Você já votou neste recurso.'
            ]);

    }

    public function test_update_resource_vote()
    {
        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->patchJson(route('resources.votes.update', $resourceVote->id), [
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

    public function test_user_cannot_update_resource_vote_of_another_user()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $resourceVote = $this->createResourceVote(['user_id' => $this->userIdsWithoutAuthUser->random()]);

        $this->patchJson(route('resources.votes.update', $resourceVote->id), [
            'vote' => false,
            'justification' => 'New justification'
        ])->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

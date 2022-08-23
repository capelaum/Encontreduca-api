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

    private array $resourceVoteKeys = [
        'id',
        'userId',
        'resourceId',
        'vote',
        'justification'
    ];

    public function test_list_resources_votes()
    {
        $this->authAdmin();

        $this->createResourceVote();

        $this->getJson(route('votes.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->resourceVoteKeys]);
    }

    public function test_user_cannot_list_resources_votes()
    {
        $this->authUser();

        $this->createResourceVote();

        $this->withExceptionHandling();

        $this->getJson(route('votes.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_resources_vote()
    {
        $this->authAdmin();

        $resourceVote = $this->createResourceVote();

        $this->getJson(route('votes.show', $resourceVote->id))
            ->assertOk()
            ->assertJsonStructure($this->resourceVoteKeys)->json();
    }

    public function test_user_cannot_show_resource_vote()
    {
        $this->authUser();

        $resourceVote = $this->createResourceVote();

        $this->withExceptionHandling();

        $this->getJson(route('votes.show', $resourceVote->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_store_resource_vote()
    {
        $this->authUser();
        $resource = $this->createResource();
        $resourceVote = ResourceVote::factory()->make();

        $data = [
            'resourceId' => $resource->id,
            'vote' => $resourceVote->vote,
            'justification' => $resourceVote->justification,
        ];

        $this->postJson(route('votes.store'), $data)
            ->assertCreated()
            ->assertJsonStructure($this->resourceVoteKeys);
    }

    public function test_user_cannot_create_two_votes_on_same_resource()
    {
        $this->authUser();

        $resource = $this->createResource();

        $resourceVote = $this->createResourceVote([
            'user_id' => Auth::user()->id,
            'resource_id' => $resource->id
        ]);

        $this->postJson(route('votes.store'), [
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
        $this->authUser();
        $resourceVote = $this->createResourceVote(['user_id' => Auth::id()]);

        $this->patchJson(route('votes.update', $resourceVote->id), [
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
        $this->withExceptionHandling();
        $this->authUser();

        $resourceVote = $this->createResourceVote(['user_id' => $this->userIdsWithoutAuthUser->random()]);

        $this->patchJson(route('votes.update', $resourceVote->id), [
            'vote' => false,
            'justification' => 'New justification'
        ])->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}

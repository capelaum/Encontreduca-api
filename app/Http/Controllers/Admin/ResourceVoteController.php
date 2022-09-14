<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\UpdateResourceVoteRequest;
use App\Http\Resources\Admin\ResourceVoteCollection;
use App\Http\Resources\Admin\ResourceVoteResource;
use App\Models\ResourceVote;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceVoteController extends Controller
{
    /**
     * Returns list of all Resources Votes.
     *
     * @return ResourceVoteCollection
     * @throws AuthorizationException
     */
    public function index(): ResourceVoteCollection
    {
        $this->authorize('isAdmin', [
            ResourceVote::class,
            'visualizar todos os votos de recursos.'
        ]);

        $resourceVotes = ResourceVote::all();

        return new ResourceVoteCollection($resourceVotes);
    }

    /**
     * Show single Resource Vote data.
     *
     * @param ResourceVote $vote
     * @return ResourceVoteResource
     * @throws AuthorizationException
     */
    public function show(ResourceVote $vote): ResourceVoteResource
    {
        $this->authorize('isAdmin', [
            ResourceVote::class,
            'visualizar esse voto de recurso.'
        ]);

        return new ResourceVoteResource($vote);
    }

    /**
     * Update Resource Vote data.
     *
     * @param UpdateResourceVoteRequest $request
     * @param ResourceVote $vote
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdateResourceVoteRequest $request,
        ResourceVote $vote
    ): JsonResponse {
        $this->authorize('isAdmin', [
            ResourceVote::class,
            'atualizar esse voto de recurso.'
        ]);

        $vote->update($request->validated());

        return response()->json(new ResourceVoteResource($vote));
    }

    /**
     * Delete Resource Vote.
     *
     * @param ResourceVote $vote
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(ResourceVote $vote): JsonResponse
    {
        $this->authorize('isAdmin', [
            ResourceVote::class,
            'deletar esse voto de recurso.'
        ]);

        $vote->delete();

        return response()->json(null, 204);
    }
}

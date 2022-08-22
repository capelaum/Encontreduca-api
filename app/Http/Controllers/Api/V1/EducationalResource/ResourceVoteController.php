<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceVoteRequest;
use App\Http\Requests\V1\EducationalResource\UpdateResourceVoteRequest;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\EducationalResource\ResourceVoteResource;
use App\Models\ResourceVote;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use function response;

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
            'listar os votos de recursos.'
        ]);

        $resourceVotes = ResourceVote::all();

        return new ResourceVoteCollection($resourceVotes);
    }

    /**
     * Show single Resource Vote data.
     *
     * @param int $id
     * @return ResourceVoteResource
     * @throws AuthorizationException
     */
    public function show(int $id): ResourceVoteResource
    {
        $this->authorize('isAdmin', [
            ResourceVote::class,
            'visualizar esse voto de recursos'
        ]);

        $resourceVote = ResourceVote::findOrFail($id);

        return new ResourceVoteResource($resourceVote);
    }

    /**
     * Create new Resource Vote and store on database.
     *
     * @param StoreResourceVoteRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreResourceVoteRequest $request): JsonResponse
    {
        $userId = $request->userId;
        $resourceId = $request->resourceId;

        $this->authorize('isRequestUser',
            [
                ResourceVote::class,
                $userId,
                'criar esse voto de recurso.'
            ]
        );

        $resourceVote = ResourceVote::where('user_id', $userId)
            ->where('resource_id', $resourceId)
            ->first();

        if ($resourceVote) {
            return response()->json([
                'message' => 'Você já votou neste recurso.'
            ], 409);
        }

        $resourceVote = ResourceVote::create($request->all());

        return response()->json($resourceVote, 201);
    }

    /**
     * Update Resource Vote data.
     *
     * @param UpdateResourceVoteRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdateResourceVoteRequest $request,
        int $id
    ): JsonResponse {
        $resourceVote = ResourceVote::findOrFail($id);

        $this->authorize('isRequestUser',
            [
                ResourceVote::class,
                $resourceVote->user_id,
                'atualizar esse voto de recurso.'
            ]
        );

        $resourceVote->update($request->only('vote', 'justification'));

        return response()->json($resourceVote);
    }
}

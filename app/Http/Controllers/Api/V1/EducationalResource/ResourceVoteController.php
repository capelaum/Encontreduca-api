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
use Illuminate\Support\Facades\Auth;
use function response;

class ResourceVoteController extends Controller
{

    /**
     * Create new Resource Vote and store on database.
     *
     * @param StoreResourceVoteRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreResourceVoteRequest $request): JsonResponse
    {
        $user = Auth::user();
        $resourceId = $request->resourceId;

        $resourceVote = ResourceVote::where('user_id', $user->id)
            ->where('resource_id', $resourceId)
            ->first();

        if ($resourceVote) {
            return response()->json([
                'message' => 'Você já votou neste recurso.'
            ], 409);
        }

        $data = $request->validated();

        $data['user_id'] = $user->id;
        $data['resource_id'] = $resourceId;

        $resourceVote = ResourceVote::create($data);

        return response()->json(new ResourceVoteResource($resourceVote), 201);
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
        $this->authorize('isRequestUser',
            [
                ResourceVote::class,
                $vote->user_id,
                'atualizar esse voto de recurso.'
            ]
        );

        $vote->update($request->validated());

        return response()->json(new ResourceVoteResource($vote));
    }
}

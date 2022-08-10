<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceVoteRequest;
use App\Http\Requests\V1\UpdateResourceVoteRequest;
use App\Http\Resources\V1\ResourceVoteCollection;
use App\Http\Resources\V1\ResourceVoteResource;
use App\Models\ResourceVote;
use Illuminate\Http\JsonResponse;

class ResourceVoteController extends Controller
{
    /**
     * Returns list of all Resources Votes.
     *
     * @return ResourceVoteCollection
     */
    public function index(): ResourceVoteCollection
    {
        $resourceVotes = ResourceVote::all();

        return new ResourceVoteCollection($resourceVotes);
    }

    /**
     * Show single Resource Vote data.
     *
     * @param int $id
     * @return ResourceVoteResource
     */
    public function show(int $id): ResourceVoteResource
    {
        $resourceVote = ResourceVote::findOrFail($id);
        return new ResourceVoteResource($resourceVote);
    }

    /**
     * Create new Resource Vote and store on database.
     *
     * @param StoreResourceVoteRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceVoteRequest $request): JsonResponse
    {
        $resourceVote = ResourceVote::create($request->all());

        return response()->json($resourceVote, 201);
    }

    /**
     * Update Resource Vote data.
     *
     * @param UpdateResourceVoteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(
        UpdateResourceVoteRequest $request,
        int $id
    ): JsonResponse {
        $resourceVote = ResourceVote::findOrFail($id);
        $resourceVote->update($request->all());

        return response()->json($resourceVote);
    }
}

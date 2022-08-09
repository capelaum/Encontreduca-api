<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceVoteRequest;
use App\Models\ResourceVote;
use Illuminate\Http\JsonResponse;

class ResourceVoteController extends Controller
{
    /**
     * Returns list of all Resources Votes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $resourceVotes = ResourceVote::all();

        return response()->json($resourceVotes);
    }

    /**
     * Show single Resource Vote data.
     *
     * @param ResourceVote $resourceVote
     * @return JsonResponse
     */
    public function show(ResourceVote $resourceVote): JsonResponse
    {
        return response()->json($resourceVote);
    }

    /**
     * Create new Resource Vote and store on database.
     *
     * @param StoreResourceVoteRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceVoteRequest $request): JsonResponse
    {
        $resourceVote = ResourceVote::create($request->validated());

        return response()->json($resourceVote, 201);
    }

    /**
     * Update Resource Vote data.
     *
     * @param StoreResourceVoteRequest $request
     * @param ResourceVote $resourceVote
     * @return JsonResponse
     */
    public function update(
        StoreResourceVoteRequest $request,
        ResourceVote $resourceVote
    ): JsonResponse {
        $resourceVote->update($request->validated());

        return response()->json($resourceVote);
    }
}

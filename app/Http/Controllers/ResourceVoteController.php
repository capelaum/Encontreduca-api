<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceVoteFormRequest;
use App\Models\ResourceVote;
use Illuminate\Http\JsonResponse;

class ResourceVoteController extends Controller
{
    /**
     * Returns list of all resources.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $resourceVotes = ResourceVote::all();

        return response()->json($resourceVotes);
    }

    /**
     * Show single Resource data.
     *
     * @param ResourceVote $resourceVote
     * @return JsonResponse
     */
    public function show(ResourceVote $resourceVote): JsonResponse
    {
        return response()->json($resourceVote);
    }

    /**
     * Create new Resource Vote and store on database
     *
     * @param StoreResourceVoteFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceVoteFormRequest $request): JsonResponse
    {
        $resourceVote = ResourceVote::create($request->validated());

        return response()->json($resourceVote, 201);
    }
}

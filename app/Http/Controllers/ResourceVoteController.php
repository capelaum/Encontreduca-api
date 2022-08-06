<?php

namespace App\Http\Controllers;

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
     * @param ResourceVote $resourceVote
     * @return JsonResponse
     */
    public function show(ResourceVote $resourceVote): JsonResponse
    {
        return response()->json($resourceVote);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceChangeFormRequest;
use App\Models\ResourceChange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviews = ResourceChange::all();

        return response()->json($reviews);
    }

    /**
     * Show single Resource Change data.
     *
     * @param ResourceChange $resourceChange
     * @return JsonResponse
     */
    public function show(ResourceChange $resourceChange): JsonResponse
    {
        return response()->json($resourceChange);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreResourceChangeFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceChangeFormRequest $request): JsonResponse
    {
        $resourceChange = ResourceChange::create($request->validated());

        return response()->json($resourceChange, 201);
    }
}

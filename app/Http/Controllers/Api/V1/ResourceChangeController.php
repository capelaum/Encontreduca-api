<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceChangeRequest;
use App\Http\Resources\V1\ResourceChangeCollection;
use App\Http\Resources\V1\ResourceChangeResource;
use App\Models\ResourceChange;
use Illuminate\Http\JsonResponse;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @return ResourceChangeCollection
     */
    public function index(): ResourceChangeCollection
    {
        $resourceChanges = ResourceChange::all();

        return new ResourceChangeCollection($resourceChanges);
    }

    /**
     * Show single Resource Change data.
     *
     * @param int $id
     * @return ResourceChangeResource
     */
    public function show(int $id): ResourceChangeResource
    {
        $resourceChange = ResourceChange::findOrFail($id);

        return new ResourceChangeResource($resourceChange);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreResourceChangeRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceChangeRequest $request): JsonResponse
    {
        $resourceChange = ResourceChange::create($request->all());

        return response()->json($resourceChange, 201);
    }
}

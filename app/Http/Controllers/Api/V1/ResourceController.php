<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceRequest;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    /**
     * Returns list of all resources.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $resources = Resource::all();

        foreach ($resources as $resource) {
            Resource::format($resource);
        }

        return response()->json($resources);
    }

    /**
     * Show single Resource data.
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function show(Resource $resource): JsonResponse
    {
        Resource::format($resource);

        return response()->json($resource);
    }

    /**
     * Create new resource and store on database
     *
     * @param StoreResourceRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = Resource::create($request->validated());

        return response()->json($resource, 201);
    }
}

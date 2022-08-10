<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceRequest;
use App\Http\Resources\V1\ResourceCollection;
use App\Http\Resources\V1\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    /**
     * Returns list of all resources.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $resources = Resource::all();

        return new ResourceCollection($resources);
    }

    /**
     * Show single Resource data.
     *
     * @param Resource $resource
     * @return ResourceResource
     */
    public function show(Resource $resource): ResourceResource
    {
        return new ResourceResource($resource);
    }

    /**
     * Create new resource and store on database
     *
     * @param StoreResourceRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = Resource::create($request->all());

        return response()->json($resource, 201);
    }
}

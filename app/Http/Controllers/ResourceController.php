<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceFormRequest;
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
            Resource::setReviews($resource);

            $resource->position = [
                'lat' => $resource->latitude,
                'lng' => $resource->longitude
            ];
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
        Resource::setReviews($resource);

        return response()->json($resource);
    }

    /**
     * Create new resource and store on database
     *
     * @param StoreResourceFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceFormRequest $request): JsonResponse
    {
        $resource = Resource::create($request->validated());

        return response()->json($resource, 201);
    }
}

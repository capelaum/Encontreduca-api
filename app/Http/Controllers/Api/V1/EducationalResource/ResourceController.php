<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceRequest;
use App\Http\Resources\V1\EducationalResource\ResourceCollection;
use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use function response;

class ResourceController extends Controller
{
    /**
     * Returns list of all resources.
     *
     */
    public function index(): JsonResponse
    {
        $resources = Resource::getAllResources();

        return response()->json($resources);
    }

    /**
     * Returns list of all resources.
     *
     */
    public function slow(): ResourceCollection
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                Resource::class,
                $request->userId,
                'criar esse recurso.'
            ]
        );

        $resource = Resource::create($request->all());

        return response()->json($resource, 201);
    }

    /**
     * Get resource reviews
     *
     * @param Resource $resource
     * @return array
     */
    public function reviews(Resource $resource): array
    {
        return Resource::getResourceReviews($resource->id);
    }

    /**
     * Get resource votes
     *
     * @param Resource $resource
     * @return array
     */
    public function votes(Resource $resource): array
    {
        return Resource::getResourceVotes($resource->id);
    }
}

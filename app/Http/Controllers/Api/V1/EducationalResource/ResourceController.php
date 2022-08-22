<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceRequest;
use App\Http\Resources\V1\EducationalResource\ResourceCollection;
use App\Http\Resources\V1\EducationalResource\ResourceResource;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\EducationalResource\ResourceVoteResource;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Resource;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @throws AuthorizationException
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
     * @return ReviewCollection
     */
    public function reviews(Resource $resource): ReviewCollection
    {
        return new ReviewCollection($resource->reviews);
    }

    /**
     * Get resource votes
     *
     * @param Resource $resource
     * @return ResourceVoteCollection
     */
    public function votes(Resource $resource): ResourceVoteCollection
    {
        return new ResourceVoteCollection($resource->votes);
    }
}

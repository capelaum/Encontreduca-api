<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ResourceCollection;
use App\Http\Resources\Admin\ResourceResource;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Models\Resource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|ResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse|ResourceCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os recursos.'
        ]);

        $resources = Resource::query();

        $resources->when($request->search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        });

        $resources = $resources->paginate(20);

        return new ResourceCollection($resources);
    }

    /**
     * Show single Resource data.
     *
     * @param Resource $resource
     * @return ResourceResource
     * @throws AuthorizationException
     */
    public function show(Resource $resource): ResourceResource
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar este recurso.'
        ]);

        return new ResourceResource($resource);
    }

    /**
     * Get resource votes
     *
     * @param Resource $resource
     * @return ResourceVoteCollection
     * @throws AuthorizationException
     */
    public function votes(Resource $resource): ResourceVoteCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os votos desse recurso.'
        ]);

        return new ResourceVoteCollection($resource->votes);
    }

    /**
     * Get resource reviews
     *
     * @param Resource $resource
     * @return ReviewCollection
     * @throws AuthorizationException
     */
    public function reviews(Resource $resource): ReviewCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os votos desse recurso.'
        ]);

        return new ReviewCollection($resource->reviews);
    }
}

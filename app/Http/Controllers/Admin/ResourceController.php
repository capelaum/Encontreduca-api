<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceVote;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\{
    ResourceCollection,
    ResourceResource,
    ResourceVoteCollection,
    ReviewCollection
};

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

        $resources->when($request->search, function ($query, $search) {
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
     * @param Request $request
     * @return ResourceVoteCollection
     * @throws AuthorizationException
     */
    public function votes(Resource $resource, Request $request): ResourceVoteCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os votos desse recurso.'
        ]);

        $votes = ResourceVote::query();

        $votes
            ->where('resource_id', $resource->id)
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $votes = $votes->paginate(20);

        return new ResourceVoteCollection($votes);
    }

    /**
     * Get resource reviews
     *
     * @param Resource $resource
     * @param Request $request
     * @return ReviewCollection
     * @throws AuthorizationException
     */
    public function reviews(Resource $resource, Request $request): ReviewCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os votos desse recurso.'
        ]);

        $reviews = Review::query();

        $reviews
            ->where('resource_id', $resource->id)
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $reviews = $reviews->paginate(20);

        return new ReviewCollection($reviews);
    }
}

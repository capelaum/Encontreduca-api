<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResourceRequest;
use App\Http\Requests\Admin\UpdateResourceRequest;
use App\Models\Resource;
use App\Models\ResourceChange;
use App\Models\ResourceComplaint;
use App\Models\ResourceUser;
use App\Models\ResourceVote;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\{ResourceChangeCollection,
    ResourceCollection,
    ResourceComplaintCollection,
    ResourceResource,
    ResourceVoteCollection,
    ReviewCollection
};

class ResourceController extends Controller
{
    /**
     * @param Request $request
     * @return ResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): ResourceCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar os recursos.'
        ]);

        $resources = Resource::query();

        $resources
            ->when($request->search,
                fn($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->approved,
                fn($query, $approved) => $query->where('approved', $approved))
            ->when($request->category,
                fn($query, $category) => $query->where('category_id', $category));

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
     * Create new resource and store on database
     *
     * @param StoreResourceRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public
    function store(
        StoreResourceRequest $request
    ): JsonResponse {
        $this->authorize('isAdmin', [
            Resource::class,
            'criar este recurso.'
        ]);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['category_id'] = $request->categoryId;

        $cloudinaryFolder = config('app.cloudinary_folder');

        $data['cover'] = $request->file('cover')
            ->storeOnCloudinary("$cloudinaryFolder/covers")
            ->getSecurePath();

        $resource = Resource::create($data);

        return response()->json(new ResourceResource($resource), 201);
    }

    /**
     * Update resource data
     *
     * @param UpdateResourceRequest $request
     * @param Resource $resource
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateResourceRequest $request, Resource $resource): JsonResponse
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'editar este recurso.'
        ]);

        $data = $request->validated();

        if ($request->categoryId) {
            $data['category_id'] = $request->categoryId;
        }

        if ($request->hasFile('cover')) {
            $cloudinaryFolder = config('app.cloudinary_folder');

            $coverUrlArray = explode('/', $resource->cover);
            $publicId = explode('.', end($coverUrlArray))[0];

            $data['cover'] = $request->file('cover')
                ->storeOnCloudinaryAs("$cloudinaryFolder/covers", $publicId)
                ->getSecurePath();
        }

        $resource->update($data);

        return response()->json(new ResourceResource($resource));
    }

    /**
     * Delete resource
     *
     * @param Resource $resource
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Resource $resource): JsonResponse
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'deletar este recurso.'
        ]);

        $cloudinaryFolder = config('app.cloudinary_folder');

        $coverUrlArray = explode('/', $resource->cover);
        $publicId = explode('.', end($coverUrlArray))[0];

        cloudinary()->destroy("$cloudinaryFolder/covers/$publicId");

        Resource::deleteResource($resource);

        return response()->json(null, 204);
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
            'visualizar as avaliações desse recurso.'
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

    /**
     * Get resource reviews
     *
     * @param Resource $resource
     * @param Request $request
     * @return ResourceComplaintCollection
     * @throws AuthorizationException
     */
    public function complaints(Resource $resource, Request $request): ResourceComplaintCollection
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar as reclamações desse recurso.'
        ]);

        $resourceComplaints = ResourceComplaint::query();

        $resourceComplaints
            ->where('resource_id', $resource->id)
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $resourceComplaints = $resourceComplaints->paginate(20);

        return new ResourceComplaintCollection($resourceComplaints);
    }

    public function changes(Resource $resource, Request $request)
    {
        $this->authorize('isAdmin', [
            Resource::class,
            'visualizar as sugestões de alterações desse recurso.'
        ]);

        $changes = ResourceChange::query();

        $changes
            ->where('resource_id', $resource->id)
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('resource', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%");
                });
            });

        $changes = $changes->paginate(20);

        return new ResourceChangeCollection($changes);
    }
}

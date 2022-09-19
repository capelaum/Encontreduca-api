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
use Illuminate\Support\Facades\Auth;
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
     */
    public function store(StoreResourceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['category_id'] = $request->categoryId;

        $cloudinaryFolder = config('app.cloudinary_folder');

        $data['cover'] = $request->file('cover')
            ->storeOnCloudinary("$cloudinaryFolder/covers")
            ->getSecurePath();

        $resource = Resource::create($data);

        return response()->json(new ResourceResource($resource), 201);
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
}

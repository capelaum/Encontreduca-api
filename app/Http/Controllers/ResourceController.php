<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceFormRequest;
use App\Models\Resource;

class ResourceController extends Controller
{
    /**
     * Returns list of all resources.
     *
     * @return Colletion
     */
    public function index()
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
     * @return Resource
     */
    public function show(Resource $resource)
    {
        Resource::setReviews($resource);

        return response()->json($resource);
    }

    /**
     * Create new resource and store on database
     *
     * @param StoreResourceFormRequest $request
     * @return Resource
     */
    public function store(StoreResourceFormRequest $request)
    {
        $resource = Resource::create($request->validated());

        return response()->json($resource, 201);
    }
}

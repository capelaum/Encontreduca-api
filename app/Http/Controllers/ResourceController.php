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
            $resource->load('reviews');
            $resource->position = [
                'lat' => $resource->latitude,
                'lng' => $resource->longitude
            ];
        }

        return response()->json($resources);
    }

    public function show(Resource $resource)
    {
        $resource->load('reviews');
        return response()->json($resource);
    }

    /**
     * Create new resource and store on database
     *
     * @return Resource
     */
    public function store(StoreResourceFormRequest $request)
    {
        $resource = Resource::create($request->validated());

        return response()->json($resource, 201);
    }
}

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

        return response()->json(['data' => $resources]);
    }

    /**
     * Create new resource and store on database
     *
     * @return Resource
     */
    public function store(StoreResourceFormRequest $request)
    {
        $resource = Resource::create($request->validated());

        return response()->json(['data' => $resource]);
    }
}

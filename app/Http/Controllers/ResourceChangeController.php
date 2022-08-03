<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceChangeFormRequest;
use App\Models\ResourceChange;
use Illuminate\Http\Request;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @return Colletion
     */
    public function index()
    {
        $reviews = ResourceChange::all();

        return response()->json($reviews);
    }

    /**
     * Show single Resource Change data.
     *
     * @param ResourceChange $resourceChange
     * @return ResourceChange
     */
    public function show(ResourceChange $resourceChange)
    {
        return response()->json($resourceChange);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreResourceChangeFormRequest $request
     * @return ResourceChange
     */
    public function store(StoreResourceChangeFormRequest $request)
    {
        $resourceChange = ResourceChange::create($request->validated());

        return response()->json($resourceChange, 201);
    }
}

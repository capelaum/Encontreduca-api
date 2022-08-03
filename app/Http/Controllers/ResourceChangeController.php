<?php

namespace App\Http\Controllers;

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
}

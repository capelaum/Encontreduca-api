<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

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
}

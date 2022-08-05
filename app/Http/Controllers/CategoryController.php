<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Returns list of all categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return response()->json($categories);
    }
}

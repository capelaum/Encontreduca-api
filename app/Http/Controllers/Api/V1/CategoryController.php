<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Returns list of all categories.
     *
     * @return CategoryCollection
     */
    public function index(): CategoryCollection
    {
        return new CategoryCollection(Category::all());
    }
}

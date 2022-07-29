<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Returns list of all categories.
     *
     * @return Colletion
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
}

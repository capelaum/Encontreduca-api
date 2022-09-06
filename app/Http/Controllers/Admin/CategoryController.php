<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryCollection;
use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Returns list of all categories.
     *
     * @return CategoryCollection
     * @throws AuthorizationException
     */
    public function index(): CategoryCollection
    {
        $this->authorize('isAdmin', [
            Category::class,
            'visualizar as categorias.'
        ]);

        return new CategoryCollection(Category::all());
    }
}

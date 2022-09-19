<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
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

    /**
     * @param Category $category
     * @return CategoryResource
     * @throws AuthorizationException
     */
    public function show(Category $category): CategoryResource
    {
        $this->authorize('isAdmin', [
            Category::class,
            'visualizar essa categoria.'
        ]);

        return new CategoryResource($category);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('isAdmin', [
            Category::class,
            'criar uma categoria.'
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($data);

        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Category $category, Request $request): JsonResponse
    {
        $this->authorize('isAdmin', [
            Category::class,
            'editar uma categoria.'
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return response()->json(new CategoryResource($category));
    }

    /**
     * @param Category $category
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('isAdmin', [
            Category::class,
            'excluir uma categoria.'
        ]);

        if ($category->resources()->count() > 0) {
            return response()->json([
                'message' => 'Não é possível excluir uma categoria que possui recursos associados.'
            ], 422);
        }

        $category->delete();

        return response()->json(null, 204);
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ResourceChangeCollection;
use App\Http\Resources\Admin\ResourceChangeResource;
use App\Models\ResourceChange;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @param Request $request
     * @return ResourceChangeCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): ResourceChangeCollection
    {
        $this->authorize('isAdmin', [
            ResourceChange::class,
            'listar as sugestões de alterações de recursos.'
        ]);

        $resourceChanges = ResourceChange::query();

        $resourceChanges->when($request->search, function ($query) use ($request) {
            $query->whereHas('resource', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });

        $resourceChanges = $resourceChanges->paginate(20);

        return new ResourceChangeCollection($resourceChanges);
    }

    /**
     * Show single Resource Change data.
     *
     * @param ResourceChange $change
     * @return ResourceChangeResource
     * @throws AuthorizationException
     */
    public function show(ResourceChange $change): ResourceChangeResource
    {
        $this->authorize('isAdmin', [
            ResourceChange::class,
            'visualizar essa sugestão de alteração de recurso.'
        ]);

        return new ResourceChangeResource($change);
    }

    /**
     * Delete a Resource Change.
     *
     * @param ResourceChange $change
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(ResourceChange $change): JsonResponse
    {
        $this->authorize('isAdmin', [
            ResourceChange::class,
            'excluir essa sugestão de alteração de recurso.'
        ]);

        if ($change->field === 'cover') {
            $coverUrlArray = explode('/', $change->new_value);
            $publicId = explode('.', end($coverUrlArray))[0];

            cloudinary()->destroy("encontreduca/covers/changes/$publicId");
        }

        $change->delete();

        return response()->json(null, 204);
    }
}

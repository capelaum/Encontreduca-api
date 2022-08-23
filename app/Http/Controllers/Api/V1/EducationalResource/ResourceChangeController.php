<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceChangeRequest;
use App\Http\Resources\V1\EducationalResource\ResourceChangeCollection;
use App\Http\Resources\V1\EducationalResource\ResourceChangeResource;
use App\Models\ResourceChange;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @return ResourceChangeCollection
     * @throws AuthorizationException
     */
    public function index(): ResourceChangeCollection
    {
        $this->authorize('isAdmin', [
            ResourceChange::class,
            'listar as sugestões de alterações de recursos.'
        ]);

        $resourceChanges = ResourceChange::all();

        return new ResourceChangeCollection($resourceChanges);
    }

    /**
     * Show single Resource Change data.
     *
     * @param int $id
     * @return ResourceChangeResource
     * @throws AuthorizationException
     */
    public function show(int $id): ResourceChangeResource
    {
        $this->authorize('isAdmin', [
            ResourceChange::class,
            'visualizar essa sugestão de alteração de recurso.'
        ]);

        $resourceChange = ResourceChange::findOrFail($id);

        return new ResourceChangeResource($resourceChange);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreResourceChangeRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceChangeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['resource_id'] = $request->resourceId;
        $data['old_value'] = $request->oldValue;
        $data['new_value'] = $request->newValue;

        $resourceChange = ResourceChange::create($data);

        return response()->json(new ResourceChangeResource($resourceChange), 201);
    }
}

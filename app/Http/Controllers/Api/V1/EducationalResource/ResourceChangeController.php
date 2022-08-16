<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceChangeRequest;
use App\Http\Resources\V1\EducationalResource\ResourceChangeCollection;
use App\Http\Resources\V1\EducationalResource\ResourceChangeResource;
use App\Models\ResourceChange;
use Illuminate\Http\JsonResponse;
use function response;

class ResourceChangeController extends Controller
{
    /**
     * Returns list of all Resource Changes.
     *
     * @return ResourceChangeCollection
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
        $this->authorize('isRequestUser',
            [
                ResourceChange::class,
                $request->userId,
                'criar essa sugestão de alteração de recurso.'
            ]
        );

        $resourceChange = ResourceChange::create($request->all());

        return response()->json($resourceChange, 201);
    }
}

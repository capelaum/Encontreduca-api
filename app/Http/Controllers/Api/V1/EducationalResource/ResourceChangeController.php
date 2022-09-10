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

        if ($request->hasFile('cover')) {
            $data['new_value'] = $request->file('cover')
                ->storeOnCloudinary('encontreduca/covers/changes')
                ->getSecurePath();
        }

        $resourceChange = ResourceChange::create($data);

        return response()->json(new ResourceChangeResource($resourceChange), 201);
    }
}

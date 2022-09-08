<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceComplaintRequest;
use App\Http\Resources\V1\EducationalResource\ResourceComplaintCollection;
use App\Http\Resources\V1\EducationalResource\ResourceComplaintResource;
use App\Models\ResourceComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

class ResourceComplaintController extends Controller
{


    /**
     * Create new Resource Complaint and store on database
     *
     * @param StoreResourceComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceComplaintRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['resource_id'] = $request->resourceId;
        $data['motive_id'] = $request->motiveId;

        $resourceComplaint = ResourceComplaint::create($data);

        return response()->json(new ResourceComplaintResource($resourceComplaint), 201);
    }
}

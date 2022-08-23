<?php

namespace App\Http\Controllers\Api\V1\EducationalResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EducationalResource\StoreResourceComplaintRequest;
use App\Http\Resources\V1\EducationalResource\ResourceComplaintCollection;
use App\Http\Resources\V1\EducationalResource\ResourceComplaintResource;
use App\Models\ResourceComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use function response;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @return ResourceComplaintCollection
     * @throws AuthorizationException
     */
    public function index(): ResourceComplaintCollection
    {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'listar as sugestões de fechamento de recursos.'
        ]);

        $resourceComplaints = ResourceComplaint::all();

        return new ResourceComplaintCollection($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param int $id
     * @return ResourceComplaintResource
     * @throws AuthorizationException
     */
    public function show(int $id): ResourceComplaintResource
    {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'visualizar essa sugestão de fechamento de recursos'
        ]);

        $resourceComplaint = ResourceComplaint::findOrFail($id);

        return new ResourceComplaintResource($resourceComplaint);
    }

    /**
     * Create new Resource Complaint and store on database
     *
     * @param StoreResourceComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceComplaintRequest $request): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                ResourceComplaint::class,
                $request->userId,
                'criar essa sugestão de fechamento de recurso.'
            ]
        );

        $resourceComplaint = ResourceComplaint::create($request->all());

        return response()->json($resourceComplaint);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceComplaintRequest;
use App\Http\Resources\V1\ResourceComplaintCollection;
use App\Http\Resources\V1\ResourceComplaintResource;
use App\Models\ResourceComplaint;
use Illuminate\Http\JsonResponse;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @return ResourceComplaintCollection
     */
    public function index(): ResourceComplaintCollection
    {
        $resourceComplaints = ResourceComplaint::all();

        return new ResourceComplaintCollection($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param int $id
     * @return ResourceComplaintResource
     */
    public function show(int $id): ResourceComplaintResource
    {
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
        $resourceComplaint = ResourceComplaint::create($request->all());

        return response()->json($resourceComplaint);
    }
}

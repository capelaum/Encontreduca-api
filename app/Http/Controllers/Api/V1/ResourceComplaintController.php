<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreResourceComplaintRequest;
use App\Models\ResourceComplaint;
use Illuminate\Http\JsonResponse;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $resourceComplaints = ResourceComplaint::all();

        return response()->json($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param ResourceComplaint $resourceComplaint
     * @return JsonResponse
     */
    public function show(ResourceComplaint $resourceComplaint): JsonResponse
    {
        return response()->json($resourceComplaint);
    }

    /**
     * Create new Resource Complaint and store on database
     *
     * @param StoreResourceComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceComplaintRequest $request): JsonResponse
    {
        $resourceComplaint = ResourceComplaint::create($request->validated());

        return response()->json($resourceComplaint);
    }
}

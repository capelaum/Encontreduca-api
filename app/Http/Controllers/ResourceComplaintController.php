<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceComplaintFormRequest;
use App\Models\ResourceComplaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param StoreResourceComplaintFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreResourceComplaintFormRequest $request): JsonResponse
    {
        $resourceComplaint = ResourceComplaint::create($request->validated());

        return response()->json($resourceComplaint);
    }
}

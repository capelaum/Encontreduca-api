<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceComplaintFormRequest;
use App\Models\ResourceComplaint;
use Illuminate\Http\Request;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @return Colletion
     */
    public function index()
    {
        $resourceComplaints = ResourceComplaint::all();

        return response()->json($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param ResourceComplaint $resourceComplaint
     * @return ResourceComplaint
     */
    public function show(ResourceComplaint $resourceComplaint)
    {
        return response()->json($resourceComplaint);
    }

    /**
     * Create new Resource Complaint and store on database
     *
     * @param StoreResourceComplaintFormRequest $request
     * @return ResourceComplaint
     */
    public function store(StoreResourceComplaintFormRequest $request)
    {
        $resourceComplaint = ResourceComplaint::create($request->validated());

        return response()->json($resourceComplaint);
    }
}

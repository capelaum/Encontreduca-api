<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewComplaintFormRequest;
use App\Models\ReviewComplaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewComplaintController extends Controller
{
    /**
     * Returns list of all Review Complaints.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviewComplaints = ReviewComplaint::all();

        return response()->json($reviewComplaints);
    }

    /**
     * Show single Review Complaint data.
     *
     * @param ReviewComplaint $reviewComplaint
     * @return JsonResponse
     */
    public function show(ReviewComplaint $reviewComplaint): JsonResponse
    {
        return response()->json($reviewComplaint);
    }

    /**
     * Create new Review Complaint and store on database
     *
     * @param StoreReviewComplaintFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewComplaintFormRequest $request): JsonResponse
    {
        $reviewComplaint = ReviewComplaint::create($request->validated());

        return response()->json($reviewComplaint);
    }
}

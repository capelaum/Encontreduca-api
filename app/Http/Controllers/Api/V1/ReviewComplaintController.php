<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreReviewComplaintRequest;
use App\Models\ReviewComplaint;
use Illuminate\Http\JsonResponse;

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
     * @param StoreReviewComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewComplaintRequest $request): JsonResponse
    {
        $reviewComplaint = ReviewComplaint::create($request->validated());

        return response()->json($reviewComplaint);
    }
}

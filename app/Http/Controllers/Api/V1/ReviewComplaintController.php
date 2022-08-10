<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreReviewComplaintRequest;
use App\Http\Resources\V1\ReviewComplaintCollection;
use App\Http\Resources\V1\ReviewComplaintResource;
use App\Models\ReviewComplaint;
use Illuminate\Http\JsonResponse;

class ReviewComplaintController extends Controller
{
    /**
     * Returns list of all Review Complaints.
     *
     * @return ReviewComplaintCollection
     */
    public function index(): ReviewComplaintCollection
    {
        $reviewComplaints = ReviewComplaint::all();

        return new ReviewComplaintCollection($reviewComplaints);
    }

    /**
     * Show single Review Complaint data.
     *
     * @param int $id
     * @return ReviewComplaintResource
     */
    public function show(int $id): ReviewComplaintResource
    {
        $reviewComplaint = ReviewComplaint::findOrFail($id);

        return new ReviewComplaintResource($reviewComplaint);
    }

    /**
     * Create new Review Complaint and store on database
     *
     * @param StoreReviewComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewComplaintRequest $request): JsonResponse
    {
        $reviewComplaint = ReviewComplaint::create($request->all());

        return response()->json($reviewComplaint);
    }
}

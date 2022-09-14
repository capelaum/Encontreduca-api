<?php

namespace App\Http\Controllers\Api\V1\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Review\StoreReviewComplaintRequest;
use App\Http\Resources\V1\Review\ReviewComplaintCollection;
use App\Http\Resources\V1\Review\ReviewComplaintResource;
use App\Models\ReviewComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

class ReviewComplaintController extends Controller
{
    /**
     * Create new Review Complaint and store on database
     *
     * @param StoreReviewComplaintRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewComplaintRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['review_id'] = $request->reviewId;
        $data['motive_id'] = $request->motiveId;

        $reviewComplaint = ReviewComplaint::create($data);

        return response()->json(new ReviewComplaintResource($reviewComplaint), 201);
    }
}

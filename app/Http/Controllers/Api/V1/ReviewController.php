<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreReviewRequest;
use App\Http\Requests\V1\UpdateReviewRequest;
use App\Http\Resources\V1\ReviewCollection;
use App\Http\Resources\V1\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Returns list of all reviews.
     *
     * @return ReviewCollection
     */
    public function index(): ReviewCollection
    {
        $reviews = Review::all();

        return new ReviewCollection($reviews);
    }

    /**
     * Show single Review data.
     *
     * @param Review $review
     * @return ReviewResource
     */
    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreReviewRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json($review, 201);
    }

    /**
     * Update review and store on database
     *
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());

        return response()->json($review);
    }

    /**
     * Delete review from database
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(null);
    }
}

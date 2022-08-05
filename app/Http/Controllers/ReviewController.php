<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewFormRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Returns list of all reviews.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviews = Review::all();

        foreach ($reviews as $review) {
            $review->user->resource_count = $review->user->resources()->count();
            $review->user->review_count = $review->user->reviews()->count();
            $review->user->resources = [];
        }

        return response()->json($reviews);
    }

    /**
     * Create new review and store on database
     *
     * @param StoreReviewFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewFormRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json($review, 201);
    }

    /**
     * Update review and store on database
     *
     * @param StoreReviewFormRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(StoreReviewFormRequest $request, Review $review): JsonResponse
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

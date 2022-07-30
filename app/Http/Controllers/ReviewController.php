<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewFormRequest;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();

        foreach ($reviews as $review) {
            $review->user->resource_count = $review->user->resources()->count();
            $review->user->review_count = $review->user->reviews()->count();
            $review->user->resources = [];
        }

        return response()->json($reviews);
    }

    public function store(StoreReviewFormRequest $request)
    {
        $review = Review::create($request->validated());

        return response()->json($review, 201);
    }

    public function update(StoreReviewFormRequest $request, Review $review)
    {
        $review->update($request->validated());

        return response()->json($review, 200);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

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
}

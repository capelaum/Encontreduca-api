<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ReviewCollection;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Returns list of all reviews.
     *
     * @param Request $request
     * @return ReviewCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): ReviewCollection
    {
        $this->authorize('isAdmin', [
            Review::class,
            'visualizar as avaliações.'
        ]);

        $reviews = Review::query();

        $reviews->when($request->search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $reviews = $reviews->paginate(20);

        return new ReviewCollection($reviews);
    }

    /**
     * Show single Review data.
     *
     * @param Review $review
     * @return ReviewResource
     * @throws AuthorizationException
     */
    public function show(Review $review): ReviewResource
    {
        $this->authorize('isAdmin', [
            Review::class,
            'visualizar esta avaliação.'
        ]);

        return new ReviewResource($review);
    }
}

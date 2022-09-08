<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Review\StoreReviewRequest;
use App\Http\Requests\V1\Review\UpdateReviewRequest;
use App\Http\Resources\Admin\ReviewCollection;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
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

    /**
     * Create new review and store on database
     *
     * @param StoreReviewRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $this->authorize('isAdmin', [
            Review::class,
            'criar avaliação.'
        ]);

        $user = auth()->user();

        $resourceId = $request->resourceId;

        $review = Review::where('user_id', $user->id)
            ->where('resource_id', $resourceId)
            ->first();

        if ($review) {
            return response()->json([
                'message' => 'Você já avaliou este recurso.'
            ], 409);
        }

        $data = $request->validated();

        $data['user_id'] = $user->id;
        $data['resource_id'] = $resourceId;

        $review = Review::create($data);

        return response()->json(new ReviewResource($review), 201);
    }

    /**
     * Update review and store on database
     *
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $this->authorize('isAdmin', [
            Review::class,
            'visualizar esta avaliação.'
        ]);

        $review->update($request->validated());

        return response()->json(new ReviewResource($review));
    }

    /**
     * Delete review from database
     *
     * @param Review $review
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('isAdmin', [
            Review::class,
            'excluir esta avaliação.'
        ]);

        $review->delete();

        return response()->json(null, 204);
    }
}

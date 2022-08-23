<?php

namespace App\Http\Controllers\Api\V1\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Review\StoreReviewRequest;
use App\Http\Requests\V1\Review\UpdateReviewRequest;
use App\Http\Resources\V1\Review\ReviewCollection;
use App\Http\Resources\V1\Review\ReviewResource;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

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
     * @throws AuthorizationException
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $user = Auth::user();
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
     */
    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
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
        $this->authorize('isRequestUser',
            [
                Review::class,
                $review->user_id,
                'excluir essa avaliação.'
            ]
        );

        $review->delete();

        return response()->json(null);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Review\StoreReviewComplaintRequest;
use App\Http\Resources\V1\Review\ReviewComplaintCollection;
use App\Http\Resources\V1\Review\ReviewComplaintResource;
use App\Models\ReviewComplaint;
use Illuminate\Http\JsonResponse;
use function response;

class ReviewComplaintController extends Controller
{
    /**
     * Returns list of all Review Complaints.
     *
     * @return ReviewComplaintCollection
     */
    public function index(): ReviewComplaintCollection
    {
        $this->authorize('isAdmin', [
            ReviewComplaint::class,
            'listar as denúncias de avaliações.'
        ]);

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
        $this->authorize('isAdmin', [
            ReviewComplaint::class,
            'visualizar essa denúncia de avaliação.'
        ]);

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
        $this->authorize('isRequestUser',
            [
                ReviewComplaint::class,
                $request->userId,
                'criar essa denúncia de avaliação.'
            ]
        );

        $reviewComplaint = ReviewComplaint::create($request->all());

        return response()->json($reviewComplaint);
    }
}

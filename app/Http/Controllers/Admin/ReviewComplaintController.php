<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ReviewComplaintCollection;
use App\Http\Resources\Admin\ReviewComplaintResource;
use App\Models\ReviewComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewComplaintController extends Controller
{
    /**
     * Returns list of all Review Complaints.
     *
     * @param Request $request
     * @return ReviewComplaintCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): ReviewComplaintCollection
    {
        $this->authorize('isAdmin', [
            ReviewComplaint::class,
            'listar as denúncias de avaliações.'
        ]);

        $reviewComplaints = ReviewComplaint::query();

        $reviewComplaints->when($request->search, function ($query, $search) {
            return $query
                ->whereHas('user', function ($query) use ($search) {
                    return $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });

        $reviewComplaints = $reviewComplaints->paginate(20);

        return new ReviewComplaintCollection($reviewComplaints);
    }

    /**
     * Show single Review Complaint data.
     *
     * @param ReviewComplaint $complaint
     * @return ReviewComplaintResource
     * @throws AuthorizationException
     */
    public function show(ReviewComplaint $complaint): ReviewComplaintResource
    {
        $this->authorize('isAdmin', [
            ReviewComplaint::class,
            'visualizar essa denúncia de avaliação.'
        ]);

        return new ReviewComplaintResource($complaint);
    }

    /**
     * Delete Review Complaint from database.
     *
     * @param ReviewComplaint $complaint
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(ReviewComplaint $complaint): Response
    {
        $this->authorize('isAdmin', [
            ReviewComplaint::class,
            'excluir essa denúncia de avaliação.'
        ]);

        $complaint->delete();

        return response()->noContent();
    }
}

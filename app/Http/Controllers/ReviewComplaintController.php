<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewComplaintFormRequest;
use App\Models\ReviewComplaint;
use Illuminate\Http\Request;

class ReviewComplaintController extends Controller
{
    /**
     * Returns list of all Review Complaints.
     *
     * @return Colletion
     */
    public function index()
    {
        $reviewComplaints = ReviewComplaint::all();

        return response()->json($reviewComplaints);
    }

    /**
     * Show single Review Complaint data.
     *
     * @param ReviewComplaint $reviewComplaint
     * @return ReviewComplaint
     */
    public function show(ReviewComplaint $reviewComplaint)
    {
        return response()->json($reviewComplaint);
    }

    /**
     * Create new Review Complaint and store on database
     *
     * @param StoreReviewComplaintFormRequest $request
     * @return ReviewComplaint
     */
    public function store(StoreReviewComplaintFormRequest $request)
    {
        $reviewComplaint = ReviewComplaint::create($request->validated());

        return response()->json($reviewComplaint);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportRequestFormRequest;
use App\Models\SupportRequest;
use Illuminate\Http\JsonResponse;

class SupportRequestController extends Controller
{
    /**
     * List all support requests.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $supportRequests = SupportRequest::all();

        return response()->json($supportRequests);
    }

    /**
     * Show a support request.
     *
     * @param SupportRequest $supportRequest
     * @return JsonResponse
     */
    public function show(SupportRequest $supportRequest): JsonResponse
    {
        return response()->json($supportRequest);
    }

    /**
     * Store a support request.
     *
     * @param StoreSupportRequestFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreSupportRequestFormRequest $request): JsonResponse
    {
        $supportRequest = SupportRequest::create($request->validated());

        return response()->json($supportRequest);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSupportRequestRequest;
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
     * @param StoreSupportRequestRequest $request
     * @return JsonResponse
     */
    public function store(StoreSupportRequestRequest $request): JsonResponse
    {
        $supportRequest = SupportRequest::create($request->validated());

        return response()->json($supportRequest);
    }
}

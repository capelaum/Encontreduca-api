<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSupportRequest;
use App\Models\Support;
use Illuminate\Http\JsonResponse;

class SupportController extends Controller
{
    /**
     * List all support requests.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $supports = Support::all();

        return response()->json($supports);
    }

    /**
     * Show a support request.
     *
     * @param SupportRequest $support
     * @return JsonResponse
     */
    public function show(Support $support): JsonResponse
    {
        return response()->json($support);
    }

    /**
     * Store a support request in database.
     *
     * @param StoreSupportRequest $request
     * @return JsonResponse
     */
    public function store(StoreSupportRequest $request): JsonResponse
    {
        $support = Support::create($request->validated());

        return response()->json($support);
    }
}

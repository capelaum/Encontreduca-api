<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSupportRequest;
use App\Http\Resources\V1\SupportCollection;
use App\Http\Resources\V1\SupportResource;
use App\Models\Support;
use Illuminate\Http\JsonResponse;

class SupportController extends Controller
{
    /**
     * List all support requests.
     *
     * @return SupportCollection
     */
    public function index(): SupportCollection
    {
        $supports = Support::all();

        return new SupportCollection($supports);
    }

    /**
     * Show a support request.
     *
     * @param SupportRequest $support
     * @return SupportResource
     */
    public function show(Support $support): SupportResource
    {
        return new SupportResource($support);
    }

    /**
     * Store a support request in database.
     *
     * @param StoreSupportRequest $request
     * @return JsonResponse
     */
    public function store(StoreSupportRequest $request): JsonResponse
    {
        $support = Support::create($request->all());

        return response()->json($support);
    }
}

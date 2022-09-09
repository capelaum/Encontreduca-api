<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSupportRequest;
use App\Http\Resources\V1\SupportCollection;
use App\Http\Resources\V1\SupportResource;
use App\Models\Support;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Store a support request in database.
     *
     * @param StoreSupportRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreSupportRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $support = Support::create($data);

        return response()->json(new SupportResource($support), 201);
    }
}

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
     * List all support requests.
     *
     * @return SupportCollection
     * @throws AuthorizationException
     */
    public function index(): SupportCollection
    {
        $this->authorize('isAdmin', [
            Support::class,
            'listar os pedidos de suporte.'
        ]);

        $supports = Support::all();

        return new SupportCollection($supports);
    }

    /**
     * Show a support request.
     *
     * @param Support $support
     * @return SupportResource
     * @throws AuthorizationException
     */
    public function show(Support $support): SupportResource
    {
        $this->authorize('isAdmin', [
            Support::class,
            'visualizar esse pedido de suporte.'
        ]);

        return new SupportResource($support);
    }

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

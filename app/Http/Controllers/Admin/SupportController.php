<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SupportCollection;
use App\Http\Resources\Admin\SupportResource;
use App\Models\Support;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * List all support requests.
     *
     * @return SupportCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): SupportCollection
    {
        $this->authorize('isAdmin', [
            Support::class,
            'listar os pedidos de suporte.'
        ]);

        $supports = Support::query();

        $supports->when($request->search, fn($query, $search) => $query
            ->whereHas('user', fn($query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
        );

        $supports = $supports->paginate(20);

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
     * Delete a support request.
     *
     * @param Support $support
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Support $support): JsonResponse
    {
        $this->authorize('isAdmin', [
            Support::class,
            'deletar esse pedido de suporte.'
        ]);

        $support->delete();

        return response()->json(null, 204);
    }
}

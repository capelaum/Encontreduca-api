<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceUserController extends Controller
{
    /**
     * Create new user resource and store on database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'resourceId' => 'required|exists:resources,id',
        ]);

        $user = Auth::user();

        $resourceUser = ResourceUser::where('user_id', $user->id)
            ->where('resource_id', $request->resourceId)
            ->first();

        if ($resourceUser) {
            return response()->json([
                'message' => 'Você já salvou este recurso.'
            ], 409);
        }

        $resourceUser = ResourceUser::create([
            'user_id' => $user->id,
            'resource_id' => $request->resourceId
        ]);

        return response()->json($resourceUser, 201);
    }

    /**
     * Delete user resource from database
     *
     * @param Resource $resource
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Resource $resource): JsonResponse
    {
        $user = Auth::user();

        $resourceUser = ResourceUser::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->first();

        if (!$resourceUser) {
            return response()->json([
                'message' => 'Você não possui esse recurso salvo.'
            ], 400);
        }

        $user->resources()->detach($resource);

        return response()->json(null, 204);
    }
}

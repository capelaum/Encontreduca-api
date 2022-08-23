<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\{
    StoreResourceUserRequest,
    UpdateUserRequest
};
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\{Review, User, Resource, ResourceUser};

class UserController extends Controller
{
    /**
     * Returns list of all users.
     *
     * @return UserCollection
     * @throws AuthorizationException
     */
    public function index(): UserCollection
    {
        $this->authorize('isAdmin', [
            User::class,
            'listar os usuários.'
        ]);

        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * Show single User data.
     *
     * @param User $user
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(User $user): UserResource
    {
        $this->authorize('isRequestUser',
            [
                User::class,
                $user->id,
                'visualizar este usuário.'
            ]
        );

        return new UserResource($user);
    }

    /**
     * Update user and store on database
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                User::class,
                $user->id,
                'atualizar esse usuário.'
            ]
        );

        $data = $request->validated();

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if (!$request->password) {
            unset($data['password']);
        }

        if (Auth::user()->email !== $request->email) {
            Auth::user()->newEmail($request->email);
            $data['email'] = auth()->user()->email;
        }

        $user->update($data);

        return response()->json(new UserResource($user));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                User::class,
                $user->id,
                'excluir esse usuário.'
            ]
        );

        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Delete user avatar from database
     *
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function deleteAvatar(User $user): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                User::class,
                $user->id,
                'excluir esse usuário.'
            ]
        );

        $user->avatar_url = null;
        $user->save();

        return response()->json(null, 204);
    }

    /**
     * Create new user resource and store on database
     *
     * @param Resource $resource
     * @return JsonResponse
     */
    public function storeResource(Resource $resource): JsonResponse
    {
        $user = Auth::user();

        $resourceUser = ResourceUser::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->first();

        if ($resourceUser) {
            return response()->json([
                'message' => 'Você já salvou este recurso.'
            ], 409);
        }

        $resourceUser = ResourceUser::create([
            'user_id' => $user->id,
            'resource_id' => $resource->id
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
    public function deleteResource(Resource $resource): JsonResponse
    {
        $user = Auth::user();

        $resourceUser = ResourceUser::where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->first();

        if(!$resourceUser) {
            return response()->json([
                'message' => 'Você não possui esse recurso salvo.'
            ], 400);
        }

        $user->resources()->detach($resource);

        return response()->json(null, 204);
    }
}

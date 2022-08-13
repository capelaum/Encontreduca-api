<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\{
    StoreResourceUserRequest,
    UpdateUserRequest
};
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\{
    User,
    Resource,
    ResourceUser
};

class UserController extends Controller
{
    /**
     * Returns list of all users.
     *
     * @return UserCollection
     */
    public function index(): UserCollection
    {
        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * Show single User data.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update user and store on database
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->all();

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if (!$request->password) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json(new UserResource($user));
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    /**
     * Delete user avatar from database
     *
     * @param User $user
     * @return JsonResponse
     */
    public function deleteAvatar(User $user): JsonResponse
    {
        $user->avatar_url = null;
        $user->save();

        return response()->json(['message' => 'Avatar deleted']);
    }

    /**
     * Create new user resource and store on database
     *
     * @param StoreResourceUserRequest $request
     * @return JsonResponse
     */
    public function storeResource(StoreResourceUserRequest $request): JsonResponse
    {
        $resourceUser = ResourceUser::create($request->validated());

        return response()->json($resourceUser, 201);
    }

    /**
     * Delete user resource from database
     *
     * @param User $user
     * @param Resource $resource
     * @return JsonResponse
     */
    public function deleteResource(User $user, Resource $resource): JsonResponse
    {
        $user->resources()->detach($resource);

        return response()->json(['message' => 'Resource deleted']);
    }
}

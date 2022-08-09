<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\{
    StoreResourceUserRequest,
    StoreUserRequest
};
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->resource_count = $user->resources()->count();
            $user->review_count = $user->reviews()->count();
        }

        return response()->json($users);
    }

    /**
     * Show single User data.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $resourcesIds = $user->resources()->pluck('id')->toArray();
        $user->resourcesIds = $resourcesIds;

        $user->resource_count = $user->resources()->count();
        $user->review_count = $user->reviews()->count();

        return response()->json($user);
    }

    /**
     * Update user and store on database
     *
     * @param StoreUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        if (!$request->password) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
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

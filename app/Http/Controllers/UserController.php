<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\{
    StoreResourceUserFormRequest,
    StoreUserFormRequest
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
     * @param StoreUserFormRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(StoreUserFormRequest $request, User $user): JsonResponse
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
     * Create new user resource and store on database
     *
     * @param StoreResourceUserFormRequest $request
     * @return JsonResponse
     */
    public function storeResource(StoreResourceUserFormRequest $request): JsonResponse
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

        return response()->json(null);
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

        return response()->json(null);
    }
}

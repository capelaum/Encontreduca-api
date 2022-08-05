<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceUserFormRequest;
use App\Http\Requests\StoreUserFormRequest;
use App\Models\Resource;
use App\Models\ResourceUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Returns list of all users.
     *
     * @return Colletion
     */
    public function index()
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
     * @return User
     */
    public function show(User $user)
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
     * @param Review $review
     * @return Review
     */
    public function update(StoreUserFormRequest $request, User $user)
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
     * @return ResourceUser
     */
    public function storeResource(StoreResourceUserFormRequest $request)
    {
        $resourceUser = ResourceUser::create($request->validated());

        return response()->json($resourceUser, 201);
    }

    /**
     * Delete user resource from database
     *
     * @param User $user
     * @param Resource $resource
     * @return void
     */
    public function deleteResource(User $user, Resource $resource)
    {
        $user->resources()->detach($resource);

        return response()->json(null);
    }

    /**
     * Delete user resource from database
     *
     * @param User $user
     * @param Resource $resource
     * @return void
     */
    public function deleteAvatar(User $user)
    {
        $user->avatar_url = null;
        $user->save();

        return response()->json(null);
    }
}

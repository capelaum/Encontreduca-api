<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceUserFormRequest;
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

    public function storeResource(StoreResourceUserFormRequest $request)
    {
        $resourceUser = ResourceUser::create($request->validated());

        return response()->json($resourceUser, 201);
    }

    public function deleteResource(User $user, Resource $resource)
    {
        $user->resources()->detach($resource);

        return response()->json(null);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

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
        $this->authorize('isAdmin', [
            User::class,
            'visualizar este usuário.'
        ]);

        return new UserResource($user);
    }
}

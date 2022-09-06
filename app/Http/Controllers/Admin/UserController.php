<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\Admin\ShowUserResource;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Returns list of all users.
     *
     * @param Request $request
     * @return UserCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): UserCollection
    {
        $this->authorize('isAdmin', [
            User::class,
            'listar os usuários.'
        ]);

        $users = User::query();

        $users->when($request->search, function ($query, $search) {
            return $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });

        $users = $users->paginate(20);

        return new UserCollection($users);
    }

    /**
     * Show single User data.
     *
     * @param User $user
     * @return ShowUserResource
     * @throws AuthorizationException
     */
    public function show(User $user): ShowUserResource
    {
        $this->authorize('isAdmin', [
            User::class,
            'visualizar este usuário.'
        ]);

        return new ShowUserResource($user);
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
        $this->authorize('isAdmin', [
            User::class,
            'atualizar este usuário.'
        ]);

        $data = $request->validated();

        if ($request->avatar) {
            if (!$user->avatar_url) {
                $data['avatar_url'] = $request->file('avatar')
                    ->storeOnCloudinary('encontreduca/avatars')
                    ->getSecurePath();
            }

            if ($user->avatar_url) {
                $avatarUrlArray = explode('/', $user->avatar_url);
                $publicId = explode('.', end($avatarUrlArray))[0];

                $data['avatar_url'] = $request->file('avatar')
                    ->storeOnCloudinaryAs('encontreduca/avatars', $publicId)
                    ->getSecurePath();
            }
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if (!$request->password) {
            unset($data['password']);
        }

        if ($request->email && $user->email !== $request->email) {
            $user->newEmail($request->email);
            $data['email'] = $user->email;
        }

        $user->update($data);

        return response()->json(new UserResource($user));
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
        $this->authorize('isAdmin', [
            User::class,
            'deletar o avatar deste usuário.'
        ]);

        if ($user->avatar_url) {
            $avatarUrlArray = explode('/', $user->avatar_url);
            $publicId = explode('.', end($avatarUrlArray))[0];

            cloudinary()->destroy("encontreduca/avatars/$publicId");
        }

        $user->avatar_url = null;
        $user->save();

        return response()->json(null, 204);
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('isAdmin', [
            User::class,
            'deletar o avatar deste usuário.'
        ]);

        $user->delete();

        return response()->json(null, 204);
    }
}

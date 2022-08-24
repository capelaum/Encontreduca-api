<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\EducationalResource\ResourceVoteResource;
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
use App\Models\{ResourceVote, Review, User, Resource, ResourceUser};

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
     * List user votes
     *
     * @return JsonResponse
     */
    public function votes(): JsonResponse
    {
        $user = Auth::user();

        $userVotes = ResourceVote::where('user_id', $user->id)->get();

        return response()->json(new ResourceVoteCollection($userVotes));
    }
}

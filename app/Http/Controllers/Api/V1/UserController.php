<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EducationalResource\ResourceVoteCollection;
use App\Http\Resources\V1\EducationalResource\ResourceVoteResource;
use Cloudinary\Cloudinary;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\{
    UpdateUserRequest
};
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\{ResourceVote, Review, User, Resource, ResourceUser};


class UserController extends Controller
{
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

        if ($request->email && Auth::user()->email !== $request->email) {
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

        $avatarUrlArray = explode('/', $user->avatar_url);
        $publicId = explode('.', end($avatarUrlArray))[0];

        cloudinary()->destroy("encontreduca/avatars/$publicId");

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

        if ($user->avatar_url) {
            $avatarUrlArray = explode('/', $user->avatar_url);
            $publicId = explode('.', end($avatarUrlArray))[0];

            cloudinary()->destroy("encontreduca/avatars/$publicId");
        }


        $user->avatar_url = null;
        $user->save();

        return response()->json(null, 204);
    }
}

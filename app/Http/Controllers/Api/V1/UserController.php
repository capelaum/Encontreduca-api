<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
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
     * @throws AuthorizationException
     */
    public function index(): UserCollection
    {
        $this->authorize('list', auth()->user());

        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * Show single User data.
     *
     * @param User $user
     * @return JsonResponse|UserResource
     * @throws AuthorizationException
     */
    public function show(User $user): JsonResponse|UserResource
    {
        $this->authorize('isOwner', [$user, 'visualizar esse usuário.']);

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
        $this->authorize('isOwner', [$user, 'atualizar esse usuário.']);

        $data = $request->all();

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if (!$request->password) {
            unset($data['password']);
        }

        if (auth()->user()->email !== $request->email) {
            auth()->user()->newEmail($request->email);
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
        $this->authorize('isOwner', [$user, 'excluir esse usuário.']);

        $user->delete();

        return response()->json(['message' => 'Usuário excluído']);
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
        $this->authorize('isOwner', [
            $user,
            'excluir a imagem de perfil desse usuário.'
        ]);

        $user->avatar_url = null;
        $user->save();

        return response()->json(['message' => 'Avatar excluído']);
    }

    /**
     * Create new user resource and store on database
     *
     * @param StoreResourceUserRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeResource(StoreResourceUserRequest $request): JsonResponse
    {
        $this->authorize('isRequestUser',
            [
                User::class,
                $request->userId,
                'adicionar esse recurso na lista de salvos'
            ]
        );

        $resourceUser = ResourceUser::create($request->validated());

        return response()->json($resourceUser, 201);
    }

    /**
     * Delete user resource from database
     *
     * @param User $user
     * @param Resource $resource
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function deleteResource(User $user, Resource $resource): JsonResponse
    {
        $this->authorize('isRequestUser', [
            User::class,
            $user->id,
            'adicionar esse recurso na lista de salvos'
        ]);

        $user->resources()->detach($resource);

        return response()->json(['message' => 'Resource deleted']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Return authenticated user data
     *
     * @param Request $request
     * @return UserResource
     */
    public function getAuthUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Store new User.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request): Response
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        event(new Registered($user));

        return response([
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();

            if ($user->tokens()->count() > 0) {
                $user->tokens()->delete();
            }

            $userToken = $user->createToken('auth', [
                'create:resource',
                'create:resource-change',
                'create:resource-complaint',
                'create:resource-vote',
                'create:review',
                'create:review-complaint',
                'create:support',
                'edit:review',
                'edit:resource-vote',
                'edit:user',
                'delete:user',
                'delete:review',
            ]);

            return response([
                'message' => "Usuário {$request->user()->name} logado com sucesso!",
                'token' => $userToken->plainTextToken,
            ], 200);
        }

        return response([
            'message' => 'Não foi possível autenticar o usuário',
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function logout()
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
}

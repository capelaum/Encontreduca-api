<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use function event;
use function response;

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
    public function login(LoginRequest $request): Response
    {
        $request->ensureIsNotRateLimited();

        if (Auth::attempt($request->validated())) {
            $user = Auth::user();

            if ($user->tokens()->count() > 0) {
                $user->tokens()->delete();
            }

            $userToken = $user->createToken('auth', ['user']);

            RateLimiter::clear($request->throttleKey());

            return response([
                'message' => "Usuário {$request->user()->name} logado com sucesso!",
                'token' => $userToken->plainTextToken,
            ], 200);
        }

        RateLimiter::hit($request->throttleKey());

        return response([
            'message' => 'Credenciais inválidas',
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     *
     * @return Response
     */
    public function logout(): Response
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
}

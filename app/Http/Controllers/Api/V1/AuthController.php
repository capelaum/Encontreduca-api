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

        Auth::login($user);

        event(new Registered($user));

        return response(null, 201);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth::guard()->attempt($request->validated())) {
            $request->session()->regenerate();

            Auth::login($request->user());

            return response([
                'message' => "Usuário {$request->user()->name} logado com sucesso",
                'session' => $request->session()->all()
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
    public function destroy(LoginRequest $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}

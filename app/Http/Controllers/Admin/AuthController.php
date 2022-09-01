<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException|AuthorizationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->ensureIsNotRateLimited();

        if (Auth::attempt($request->validated())) {
            $this->authorize('is_admin');

            $admin = Auth::user();

//            if (!$admin->is_admin) {
//                return response()->json([
//                    'message' => 'Você não tem permissão.',
//                ], 401);
//            }

            $adminToken = $admin->createToken('admin', ['admin'])->plainTextToken;

            RateLimiter::clear($request->throttleKey());

            return response()->json([
                'message' => "Administrador {$admin->name} logado com sucesso!",
                'token' => $adminToken,
            ], 200);
        }

        RateLimiter::hit($request->throttleKey());

        return response()->json([
            'message' => 'Credenciais inválidas',
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
}

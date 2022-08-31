<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
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
     * Register new User.
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
     * @throws ValidationException
     */
    public function login(LoginRequest $request): Response
    {
        $request->ensureIsNotRateLimited();

        if (Auth::attempt($request->validated())) {
            $user = Auth::user();

            $userToken = $user->createToken('auth', ['user'])->plainTextToken;

            RateLimiter::clear($request->throttleKey());

            return response([
                'message' => "Usuário {$user->name} logado com sucesso!",
                'token' => $userToken,
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

    /**
     * Login with a social provider
     *
     * @param string $provider
     * @param Request $request
     * @return JsonResponse
     */
    public function loginWithProvider(string $provider, Request $request): JsonResponse
    {
        $request->validate([
            'accessToken' => 'required|string',
        ]);

        $validateProvider = $this->validateProvider($provider);

        if (!is_null($validateProvider)) {
            return response()->json([
                'message' => 'Provider inválido',
            ], 400);
        }

        try {
            $providerUser = Socialite::driver($provider)->userFromToken($request->accessToken);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Token inválido.',
            ], 400);
        }

        $providerId = Provider::where('provider_id', $providerUser->getId())->first();

        if ($providerId) {
            $user = $providerId->user;
        }

        if (!$providerId) {

            $user = User::where('email', $providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'avatar_url' => $providerUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now()
                ]);
            }

            $user->providers()->updateOrCreate([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $providerUser->getId(),
            ]);
        }

        Auth::login($user);

        $userToken = $user->createToken('auth', ['user'])->plainTextToken;

        return response()->json([
            'message' => "Usuário {$user->name} logado com sucesso!",
            'token' => $userToken,
        ], 200);
    }

    /**
     * @param $provider
     * @return JsonResponse|void
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['github', 'google'])) {
            return response()->json([
                'error' => 'Por favor, faça login com um provedor válido: github ou google.'
            ], 422);
        }
    }
}

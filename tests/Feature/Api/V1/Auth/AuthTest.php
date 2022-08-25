<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private array $userKeys = [
        'id',
        'name',
        'avatarUrl',
        'reviewCount',
        'resourcesIds'
    ];

    public function test_get_auth_user()
    {
        $this->authUser();

        $user = Auth::user();
        $user->reviewCount = $user->reviews()->count();
        $user->resourcesIds = $user->resources()->pluck('id')->toArray();

        $this->getJson(route('auth.user'))
            ->assertOk()
            ->assertJsonStructure($this->userKeys)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'avatarUrl' => $user->avatar_url,
                'reviewCount' => $user->reviewCount,
                'resourcesIds' => $user->resourcesIds
            ]);

    }

    public function test_register()
    {
        Event::fake();

        $user = User::factory(['password' => 'password'])->make();

        $this->postJson(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'confirmPassword' => $user->password
        ])->assertCreated()
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'avatarUrl',
                    'reviewCount',
                    'resourcesIds'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        Event::assertDispatched(Registered::class);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $user = $this->createUser();

        $this->withExceptionHandling();

        $this->postJson(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'confirmPassword' => $user->password
        ])->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email'
                ]
            ]);
    }

    public function test_login()
    {
        $user = $this->createUser();

        $user->createToken('auth', ['user']);

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk()
            ->assertJsonStructure([
                'message',
                'token'
            ]);
    }

    public function test_login_attempts_are_throttled()
    {
        $user = $this->createUser();

        $this->withExceptionHandling();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('auth.login'), [
                'email' => $user->email,
                'password' => 'wrong-password'
            ])
                ->assertUnauthorized()
                ->assertJson(['message' => 'Credenciais inválidas']);
        }

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrong-password'
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $this->postJson(route('auth.login', [
            'email' => 'john_doe@email.com',
            'password' => 'password'
        ]))
            ->assertUnauthorized()
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = $this->createUser();

        $this->postJson(route('auth.login', [
            'email' => $user->email,
            'password' => 'secret123'
        ]))
            ->assertUnauthorized()
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_logout()
    {
        $this->authUser();

        $this->postJson(route('auth.logout'))
            ->assertOk()
            ->assertJson([
                'message' => 'Logout realizado com sucesso!'
            ]);
    }

    public function test_user_cannot_logout_if_unauthenticated()
    {
        $this->authUser();

        $this->postJson(route('auth.logout'))
            ->assertOk()
            ->assertJson([
                'message' => 'Logout realizado com sucesso!'
            ]);
    }
}

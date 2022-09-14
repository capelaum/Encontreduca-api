<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login()
    {
        $admin = $this->createUser(['is_admin' => true]);

        $this->postJson(route('admin.auth.login'), [
            'email' => $admin->email,
            'password' => 'password'
        ])->assertOk()
            ->assertJsonStructure([
                'message',
                'token'
            ]);
    }

    public function test_user_cannot_make_admin_login()
    {
        $admin = $this->createUser();

        $this->postJson(route('admin.auth.login'), [
            'email' => $admin->email,
            'password' => 'password'
        ])->assertUnauthorized();
    }

    public function test_admin_login_attempts_are_throttled()
    {
        $admin = $this->createUser(['is_admin' => true]);

        $this->withExceptionHandling();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('auth.login'), [
                'email' => $admin->email,
                'password' => 'wrong-password'
            ])
                ->assertUnauthorized()
                ->assertJson(['message' => 'Credenciais inválidas']);
        }

        $this->postJson(route('admin.auth.login'), [
            'email' => $admin->email,
            'password' => 'wrong-password'
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    public function test_admin_login_with_invalid_email()
    {
        $this->postJson(route('admin.auth.login', [
            'email' => 'john_doe@email.com',
            'password' => 'password'
        ]))
            ->assertUnauthorized()
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_admin_cannot_login_with_wrong_password()
    {
        $admin = $this->createUser(['is_admin' => true]);

        $this->postJson(route('auth.login', [
            'email' => $admin->email,
            'password' => 'secret123'
        ]))
            ->assertUnauthorized()
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_admin_logout()
    {
        $this->authAdmin();

        $this->postJson(route('admin.auth.logout'))
            ->assertOk()
            ->assertJson([
                'message' => 'Logout realizado com sucesso!'
            ]);
    }

    public function test_admin_cannot_logout_if_unauthenticated()
    {
        $this->withExceptionHandling();

        $this->postJson(route('admin.auth.logout'))
            ->assertUnauthorized();
    }
}

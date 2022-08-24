<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_email()
    {
        $user = $this->createUser();

        $this->postJson(route('password.email'), [
            'email' => $user->email
        ])->assertOk()
            ->assertJson([
                "status" => "Enviamos seu link de redefinição de senha por e-mail!"
            ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);
    }

    public function test_cannot_reset_password_with_invalid_email()
    {
        $this->postJson(route('password.email'), [
            'email' => 'john_doe@email.com'
        ])->assertStatus(400)
            ->assertJson([
                "email" => "Não encontramos um usuário com esse endereço de e-mail."
            ]);
    }

    public function test_reset_password_link()
    {
        $user = $this->createUser();

        $token = $this->createPasswordResetToken($user);

        $this->getJson(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]))
            ->assertRedirect(config('app.frontend_url') . "/?token={$token}&email={$user->email}");
    }

    public function test_reset_password_update()
    {
        $user = $this->createUser();
        $token = $this->createPasswordResetToken($user);

        $this->postJson(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'confirmPassword' => 'password'
        ])->assertOk()
            ->assertJson([
                "status" => "Sua senha foi redefinida!"
            ]);
    }
}

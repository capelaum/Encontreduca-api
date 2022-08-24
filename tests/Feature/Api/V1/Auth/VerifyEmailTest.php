<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_email_can_be_verified()
    {
        Event::fake();

        $user = $this->createUser([
            'email_verified_at' => null
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1('taylor@laravel.com'),
            ]
        );

        $this->actingAs($user)
            ->get($signedUrl)
            ->assertRedirect(config('app.frontend_url') . '/?emailVerified=true');

        Event::assertDispatched(Verified::class);
    }

    public function test_redirected_if_email_is_already_verified()
    {
        $user = $this->createUser();

        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1('taylor@laravel.com'),
            ]
        );

        $this->actingAs($user)
            ->get($signedUrl)
            ->assertRedirect(config('app.frontend_url'));
    }

    public function test_verify_email_send()
    {
        $user = $this->createUser([
            'email_verified_at' => null
        ]);

        $this->actingAs($user)
            ->postJson(route('verification.send'))
            ->assertOk()
            ->assertJson([
                'message' => 'Email de verificação reenviado com sucesso'
            ]);
    }
}
